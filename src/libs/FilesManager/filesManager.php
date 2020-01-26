<?php

	namespace LIB
	{
		class FilesManager
		{
			const MAX_SIZE = 102400000; //100MB
			const PNG_COMPRESS = 9;
			const JPG_COMPRESS = 22;

			private $dir;

			public function __construct(string $dir)
			{
				$this->dir = $dir;
			}

			public function changeDirectory(string $dir): void
			{
				$this->dir = $dir;
			}

			public function scanDirectory(string $dir = ''): array
			{
				return array_diff(scandir($this->dir.$dir), ['.' , '..']);
			}

			public function addDirectory(string $name = ''): void
			{
				mkdir($this->dir.$name);
			}

			public function removeDirectory(string $name): void
			{
				self::rrmdir($this->dir.$name);
			}

			public function removeMainDirectory(): void
			{
				self::rrmdir($this->dir);
			}

			public function addFile(array $file): string
			{
				$ext = '.'.pathinfo($file['name'])['extension'];
				$fileName = sha1_file($file['tmp_name']).$ext;

				move_uploaded_file($file['tmp_name'], $this->dir.$fileName);
				return $fileName;
			}

			public function deleteFile(string $name): void
			{
				if ($this->fileExists($name))
					unlink($this->dir.$name);
			}

			public function fileExists(string $name): bool
			{
				return file_exists($this->dir.$name);
			}

			public function hasError(array $file): bool
			{
				return $file['error'] != 0;
			}

			public function hasMaxSize(array $file): bool
			{
				return $file['size'] > self::MAX_SIZE;
			}

			public function isImage(array $file): bool
			{
				return getImageSize($file['tmp_name']);
			}

			public function isDirectory(string $dir): bool
			{
				return is_dir($this->dir.$dir);
			}

			/*It Breaks animations in GIF's and alpha canal in PNG's*/
			public function compressImage(string $name, double $newWidth = 1, double $newHeight = 1): void
			{
				if ($this->fileExists($name))
				{
					ini_set('memory_limit', '-1');
					$source = $this->dir.$name;
					$info = getimagesize($source);
					$width = $info[0];
					$height = $info[1];

					if ($newWidth <= 1 && $newHeight <= 1)
					{
						$newWidth = round($width * $newWidth);
						$newHeight = round($height * $newHeight);
					}

					if ($info['mime'] == 'image/jpeg')
					{
						$orginal = imagecreatefromjpeg($source);
						$compress = function(&$image, $source){
							imagejpeg($image, $source, self::JPG_COMPRESS);
						};
					}
					else if ($info['mime'] == 'image/gif')
					{
						$orginal = imagecreatefromgif($source);
						$compress = function(&$image, $source){
							imagegif($image, $source);
						};
					}
					else if ($info['mime'] == 'image/png')
					{
						$orginal = imagecreatefrompng($source);
						$compress = function(&$image, $source){
							imagepng($image, $source, self::PNG_COMPRESS);
						};
					}

					$copy = imagecreatetruecolor($newWidth, $newHeight);
					imagecopyresized($copy, $orginal, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
					$this->deleteFile($source);
					$compress($copy, $source);

					imagedestroy($orginal);
					imagedestroy($copy);
				}
			}

			static public function getPostFiles(): array
			{
				$files = $_FILES;
				$files2 = [];
				foreach ($files as $input => $infoArr)
				{
					$filesByInput = [];
					foreach ($infoArr as $key => $valueArr)
					{
						if (is_array($valueArr))
						{
							foreach ($valueArr as $i => $value)
								$filesByInput[$i][$key] = $value;
						}
						else
						{
							$filesByInput[] = $infoArr;
							break;
						}
					}
					$files2 = array_merge($files2, $filesByInput);
				}
				$files3 = [];
				foreach($files2 as $file)
					if (!$file['error']) $files3[] = $file;
				return $files3;
			}

			static private function rrmdir(string $dir): void
			{
				if (is_dir($dir))
				{
					$objects = scandir($dir);
					foreach ($objects as $object)
					{
						if ($object != "." && $object != "..")
						{
							if (is_dir($dir."/".$object))
								self::rrmdir($dir."/".$object);
							else unlink($dir."/".$object);
						}
					}
					rmdir($dir);
				}
			}
		}
	}

?>
