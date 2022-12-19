#!/bin/bash

# Contact: https://alexishenry.eu/contact
# Request: https://github/AlxsHenry/cdn/issues

# This script is used to upload files to the cdn.
# The files placed in the folder "uploads" will be uploaded to the cdn.
# Some files are not allowed to be uploaded to the cdn, a check is done on the file name and extension. But it is not 100% safe.

# Steps to follow to use this script:
# 1. Copy the file you want to upload in the folder "uploads".
# 2. For safety reasons be sure you doens't have any special characters in the name of the file.
# 3. Run this script.
# 4. The file will be uploaded to the cdn and the link to the file will be displayed.
# 5. The file will be deleted from the folder "uploads" after the upload.
# 6. You can now use the link to the file in your code.
#    - For example: <img src="https://cdn.example.com/shared/images/image.jpg" alt="example" />
# 	 - For example: <a href="https://cdn.example.com/shared/images?file=image.jpg">Download</a>
# 7. You can also access to the file from the inteface of the cdn (cf: cdn.alexishenry.eu).

# Some warnings:
# - Don't upload scripts or php files to the cdn. It's not safe.
# - The file will be renamed to lowercase and without spacing.
# - If the file already exists on the cdn, it will be replaced.
# - The file will be uploaded to the cdn in the folder "shared".
# - The file will be deleted from the folder "uploads" after the upload.

# Don't forget to change the variables in the file ".env" before using this script.

# For more details about the cdn :
# - Read the readme.
# - Read the source code.

source .env;
source utils/tools.sh
source utils/func.sh;

# Options (you can add or remove options in the file "utils/args.sh"):
# -h, --help: Display the help.
# -u, --update: Check for updates.
# -d, --debug: Display the debug messages.
# -f, --force: Force the upload of the files (skip some checks).

source utils/args.sh;

# Allowed extensions:
# - File: pdf md txt
# - Image: jpg jpeg png gif svg
# - Video: mp4 webm
# You can add or remove extensions in the variables below.

file_allowed_extensions="pdf md txt yml json yaml"
image_allowed_extensions="jpg jpeg png gif svg"
video_allowed_extensions="mp4 webm"

# You can customize some settings in the variables below:

max_size_file=50000000 # 50MB

# The script is not perfect, but it works. If you have any suggestions, feel free to contact me.

# Check if filename are corresponding with the files in the folder "uploads".
filenameCorrespondance ${files};

# Loop through the files in the folder "uploads".
foreach ${files};