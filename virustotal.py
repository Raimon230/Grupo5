import argparse
import asyncio
import itertools
import os
import sys
import vt

# Agrega tu API Key aquí
API_KEY = '6269c415a16ff9ecb6d54ce699e40159628836e6d9780a057c93d2ac5478cf59'

async def get_files_to_upload(queue, path):
    """Finds which files will be uploaded to VirusTotal."""
    if os.path.isfile(path):
        await queue.put(path)
        return 1

    n_files = 0
    with os.scandir(path) as it:
        for entry in it:
            if not entry.name.startswith(".") and entry.is_file():
                await queue.put(entry.path)
                n_files += 1
    return n_files


async def upload_hashes(queue, apikey):
    """Uploads selected files to VirusTotal."""
    return_values = []

    async with vt.Client(apikey) as client:
        while not queue.empty():
            file_path = await queue.get()
            with open(file_path, encoding="utf-8") as f:
                analysis = await client.scan_file_async(file=f)
                print(f"File {file_path} uploaded.")
                queue.task_done()
                return_values.append((analysis, file_path))

    return return_values


async def process_analysis_results(apikey, analysis, file_path):
    try:
        async with vt.Client(apikey) as client:
            completed_analysis = await client.wait_for_analysis_completion(analysis)
            print(f"{file_path}: {completed_analysis.stats}")
            print(f"analysis id: {completed_analysis.id}")
    except Exception as e:
        print(f"Error processing analysis results: {e}")


async def main():
    parser = argparse.ArgumentParser(description="Upload files to VirusTotal.")

    parser.add_argument(
        "--path", required=True, help="path to the file/directory to upload."
    )
    parser.add_argument(
        "--workers",
        type=int,
        required=False,
        default=4,
        help="number of concurrent workers",
    )
    args = parser.parse_args()

    if not os.path.exists(args.path):
        print(f"ERROR: file {args.path} not found.")
        sys.exit(1)

    queue = asyncio.Queue()
    n_files = await get_files_to_upload(queue, args.path)

    worker_tasks = []
    for _ in range(min(args.workers, n_files)):
        worker_tasks.append(asyncio.create_task(upload_hashes(queue, API_KEY)))

    print("Waiting for analysis results...")
    analyses = itertools.chain.from_iterable(await asyncio.gather(*worker_tasks))
    await asyncio.gather(
        *[
            asyncio.create_task(process_analysis_results(API_KEY, a, f))
            for a, f in analyses
        ]
    )


if __name__ == "__main__":
    asyncio.run(main())