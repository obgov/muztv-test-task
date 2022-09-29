<?php

/*
 * Вариант реализации 2.
 */
class ResizeImage
{
    private string $extension;
    private int $width;
    private int $height;
    private Imagick $imagick;
    private array $allowedMimeTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];

    public function __construct(string $filename, int $width, int $height)
    {
        $this->getMime($filename);

        $this->width = $width;
        $this->height = $height;

        $this->imagick = new Imagick(realpath($filename));
    }

    private function getMime(string $filename): void
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->extension = finfo_file($finfo, $filename);

        if (!in_array($this->extension, $this->allowedMimeTypes)) {
            throw new Exception('Файл не является допустимым изображением');
        }
    }


    public function render(): void
    {
        switch ($this->extension) {
            case 'image/jpg':
            case 'image/jpeg':
                header('Content-Type: image/jpeg');
                break;

            case 'image/gif':
                header('Content-Type: image/gif');
                break;

            case 'image/png':
                header('Content-Type: image/png');
                break;
        }
        $this->imagick->resizeImage($this->width, $this->height, Imagick::FILTER_LANCZOS, 1, true);
        echo $this->imagick->getImageBlob();
    }
}

$img = new ResizeImage('cat.jpg', 2000, 2000);
$img->render();
