<?php

define("PLUGIN_DIR_NAME", 'plugin-x');

if (!empty($_SERVER['HTTP_HOST'])) {
    return;
}

define("ARCHIVE_PATH", __DIR__ . '/archives');
define("PLUGIN_PATH", __DIR__ . '/' . PLUGIN_DIR_NAME);

function incrementPatchVersion($file)
{
    $content = file_get_contents($file);
    if (!$content) {
        throw new Exception("Can not read plugin main file: {$file}");
    }

    if (preg_match('/^Version:\s*(\d+\.\d+\.)(\d+)/m', $content, $matches)) {
        $newVersion = $matches[1] . ($matches[2] + 1);
        $content = str_replace($matches[0], "Version: " . $newVersion, $content);
        file_put_contents($file, $content);
        return $newVersion;
    } else {
        throw new Exception("Version not found: {$file}");
    }
}

function zipPlugin($srcDir, $zipFile)
{
    $srcDir = realpath($srcDir);
    $pluginName = basename($srcDir);

    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        throw new Exception("Can not open the zip file: {$zipFile}");
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
            function ($file, $key, $iterator) {
                // node_modulesフォルダをスキップ
                if ($iterator->hasChildren() && $file->getFilename() === 'node_modules') {
                    return false;
                }
                return true;
            }
        ),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = str_replace($srcDir, '', $filePath);
            $relativePath = ltrim($relativePath, '/');

            $zip->addFile($filePath, "$pluginName/$relativePath");
        }
    }

    $zip->close();
}

function getMainFilePath($pluginFolder)
{
    $pluginName = basename($pluginFolder);
    $pluginDir = dirname($pluginFolder);
    return "{$pluginDir}/{$pluginName}/{$pluginName}.php";
}

function generateZipPath($pluginFolder, $version)
{
    $pluginName = basename($pluginFolder);
    return ARCHIVE_PATH . '/' . $pluginName . '-' . $version . '.zip';
}

try {
    echo "\n";
    echo "************************\n";
    echo "* Plugin archiver *\n";
    echo "************************\n";
    echo "\n";
    echo "Updateing and archiving the plugin...\n";
    $mainFilePath = getMainFilePath(PLUGIN_PATH);
    $newVersion = incrementPatchVersion($mainFilePath);
    echo "New Version: {$newVersion}\n";

    $zipFilePath = generateZipPath(PLUGIN_PATH, $newVersion);
    zipPlugin(PLUGIN_PATH, $zipFilePath);
    echo "Archive success: {$zipFilePath}\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
