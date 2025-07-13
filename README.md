# Project Structure Generator

## Description

The **Project Structure Generator** is a PHP command-line tool designed to generate and display the hierarchical structure of a project directory. It allows developers to quickly visualize the files and folders in a project in a formatted and organized manner.

## Features

- Recursively analyzes directories
- Displays project structure with appropriate indentation
- Outputs the structure to a text file

## Installation

1. Clone the repository:

`git clone https://github.com/sjeuneje/project-structure-generator.git`

2. Navigate to the project directory:

`cd project-structure-generator`

3. Install dependencies using Composer:

`composer install`

### Usage
To generate the structure of a directory, run the command-line interface:

`php bin/generate-structure [source-path] [output-file]`

### Arguments
source-path Path to the directory to scan (default: current directory)
output-file Path for the output file (default: project-structure.txt)

### Options
-h, --help Show the help message
-v, --version Show version information

### Examples
php bin/generate-structure
php bin/generate-structure /path/to/project
php bin/generate-structure . my-structure.txt