<?php

namespace ProjectStructureGenerator;

/**
 * Generate a text file containing the hierarchical structure of a project directory.
 *
 * This class scans a directory recursively and creates a formatted output showing
 * the project structure with proper indentation and organization (directories first,
 * then files).
 */
class Generator
{
    /**
     * List of directory and file names that should be ignored during scanning.
     *
     * These are common directories and files that are typically not relevant
     * for project structure documentation (vendor dependencies, build artifacts,
     * IDE files, etc.).
     *
     * @var array<string>
     */
    private array $ignoredEntries = [
        'vendor',
        'node_modules',
        '.idea',
        '.vscode',
        'dist',
        'build',
        'coverage',
        'tmp',
        'temp',
        'cache',
        'storage',
        '.DS_Store',
        'logs',
        'framework',
        'hot',
        'public',
        'bootstrap',
        '.git'
    ];

    /**
     * The formatted output string containing the project structure.
     *
     * This string is built progressively as directories are scanned and
     * will be written to the output file.
     *
     * @var string
     */
    private string $output = '';

    /**
     * Generate the project structure and save it to a file.
     *
     * @param string $sourcePath The root directory to scan (default: current directory)
     * @param string $outputFile The output file path (default: 'project-structure.txt')
     *
     * @throws \RuntimeException If the source path doesn't exist or isn't readable
     */
    public function generate(string $sourcePath = '.', string $outputFile = 'project-structure.txt'): string
    {
        if (!is_dir($sourcePath) || !is_readable($sourcePath)) {
            throw new \RuntimeException("Source path '{$sourcePath}' is not a readable directory.");
        }

        $this->scanDirectory($sourcePath, 0);
        file_put_contents($outputFile, $this->output);

        echo $this->output . "\n";

        return $this->output;
    }

    /**
     * Recursively scan a directory and build the formatted output.
     *
     * Directories are processed first, followed by files. Each level of nesting
     * is indicated by dashes (-) for proper visual hierarchy.
     *
     * @param string $path The directory path to scan
     * @param int $level The current nesting level (0 for root)
     */
    private function scanDirectory(string $path, int $level): void
    {
        $entries = $this->scanCurrentDir($path);
        if ($entries === false) {
            return;
        }

        $directories = [];
        $files = [];

        foreach ($entries as $entry) {
            if (in_array($entry, $this->ignoredEntries)) {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($fullPath)) {
                $directories[] = $entry;
            } else {
                $files[] = $entry;
            }
        }

        foreach ($directories as $directory) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $directory;
            $this->output .= ($level === 0 ? "" : str_repeat('-', $level) . ' ') . $directory . "/\n";
            $this->scanDirectory($fullPath, $level + 1);
        }

        foreach ($files as $file) {
            $this->output .= ($level === 0 ? "" : str_repeat('-', $level) . ' ') . $file . "\n";
        }
    }

    /**
     * Get the contents of a directory, excluding '.' and '..' entries.
     *
     * @param string $dirName The directory path to scan
     * @return array<string>|false Array of directory entries or false on failure
     */
    private function scanCurrentDir(string $dirName): array|false
    {
        $entries = scandir($dirName);
        if ($entries === false) {
            return false;
        }

        return array_filter($entries, function($entry) {
            return $entry !== '.' && $entry !== '..';
        });
    }
}
