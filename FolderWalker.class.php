<?php 
/**
 * an abstract class that walk recursively through a given path.
 * Activating the walking can be done by calling the StartWalking($pathtofolder) function
 * This class contains 4 abstract function that the user needs to implements:
 * 1. FolderFilter: a function that will be called when walking process finds a folder. Expected to return true of false
 * 2. ProcessFolder: a function that will be called if FolderFilter function returns true
 * 3. FileFilter: behave the same as FolderFilter but instead of folder, this function subject is file
 * 4. ProcessFilter: a function that will be called if FileFilter returns true
 */
/**
 * Todo: repair the $mStringFolderTree. It still didn't print the tree of the folder correctly 
 */
abstract class FolderWalker {

	var $mFolderPath;
	var $mDirIter;
	var $mStringFolderTree;
	
	public function FileWalker() {
		$mStringFolderTree = ""; 
	}
	
	/**
	 * the function to activate walking process. Default setting is walk recursively, but can be change easily
	 */
	public function StartWalking($pFolderPath,$pRecursiveWalk = true) {
		$mFolderPath = $pFolderPath;	
		$mDirIter = new DirectoryIterator($mFolderPath);
		return $this->WalkDirectory($mDirIter,$pRecursiveWalk);
	}

	/**
	 * the recursive function that walks through a directory of given path
	 */
	protected function WalkDirectory(DirectoryIterator $Directory,$pRecursiveWalk,$depth = 0) {
		$this->mStringFolderTree = str_repeat('&nbsp;', ($depth * 5)).$Directory->getPathName().'<br />';
		while ($Directory->valid()) {
			$node = $Directory->current();
			if ($node->isDir() && $node->isReadable() && !$node->isDot()) {
				if($this->FolderFilter($Directory, $node)) {
					$this->ProcessFolder($Directory, $node);				
				}
				if($pRecursiveWalk) {
					$this->mStringFolderTree .= $this->WalkDirectory(new DirectoryIterator($node->getPathname()),$pRecursiveWalk, $depth + 1);
				}
			}
			elseif ( $node->isFile() ) {
				if($this->FileFilter($Directory, $node)) {
					$this->ProcessFile($Directory, $node);
				}
				$this->mStringFolderTree .= str_repeat('&nbsp;', ((1 + $depth) * 5)).$node->getFilename();
			}
			$Directory->next();      
		}
		return $this->mStringFolderTree;
	}
	
	/**
	 * user need to implement this. Expected to return true or false
	 */
	abstract protected function FolderFilter(DirectoryIterator $pParent, DirectoryIterator $pNode);
	
	/**
	 * user need to implement this. This is a procedure
	 */
	abstract protected function ProcessFolder(DirectoryIterator $pParent, DirectoryIterator $pNode);
	
	/**
	 * user need to implement this. Expected to return true or false
	 */
	abstract protected function FileFilter(DirectoryIterator $pParent, DirectoryIterator $pNode);
	
	/**
	 * user need to implement this. This is a procedure
	 */
	abstract protected function ProcessFile(DirectoryIterator $pParent, DirectoryIterator $pNode);
}

?>
