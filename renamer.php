<?php 
require_once 'FolderWalker.class.php';

/**
 *  rename file with extension dds and png to be the same as the folder name
 */
class Renamer extends FolderWalker {
	protected function FolderFilter(DirectoryIterator $pParent, DirectoryIterator $pNode) { }
	
	protected function ProcessFolder(DirectoryIterator $pParent, DirectoryIterator $pNode) { }
	
	protected function FileFilter(DirectoryIterator $pParent, DirectoryIterator $pNode) {
		$returnvalue = false;
		$filename = $pNode->getFilename();
		$patternDDS = '/\.dds$/';
		$patternPNG = '/\.png$/';
		if(preg_match($patternDDS, $filename)){ 
			$returnvalue = true;
		} else if(preg_match($patternPNG, $filename)){
			$returnvalue = true;	
		}
		return $returnvalue;
	}
	protected function ProcessFile(DirectoryIterator $pParent, DirectoryIterator $pNode) {
		$oldname = $pNode->getPathname();
		$newname = $pParent->getPath().'\\'.$this->GetLastFolderName($pParent->getPath()).'.dds';
		rename($oldname,$newname);
		echo '<p>rename <b>' .$oldname. '</b> to <b>' .$newname.'<br/></p>';
	}
	
	private function GetLastFolderName($fullpath) {
		$folders = explode('\\',$fullpath);
		return $folders[count($folders)-1];
	}	
}


$tRenamer = new Renamer();
print $tRenamer->StartWalking('C:\\PathToFolder');


?>
