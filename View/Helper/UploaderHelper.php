<?php
/* SVN FILE: $Id$ */
/**
 * アップローダーヘルパー
 *
 * PHP versions 5
 *
 * Baser :  Basic Creating Support Project <http://basercms.net>
 * Copyright 2008 - 2013, Catchup, Inc.
 *								1-19-4 ikinomatsubara, fukuoka-shi
 *								fukuoka, Japan 819-0055
 *
 * @copyright		Copyright 2008 - 2013, Catchup, Inc.
 * @link			http://basercms.net BaserCMS Project
 * @package			uploader.views.helper
 * @since			Baser v 0.1.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://basercms.net/license/index.html
 */
/**
 * アップローダーヘルパー
 *
 * @package			baser.plugins.uploader.views.helpers
 */
class UploaderHelper extends AppHelper {
/**
 * アップロードファイルの保存URL
 * 
 * @var		string
 * @access	public
 */
	public $savedUrl = '';
/**
 * アップロードファイルの保存パス
 * 
 * @var		string
 * @access	public
 */
	public $savePath = '';
/**
 * ヘルパー
 * 
 * @var		array
 * @access	public
 */
	public $helpers = array('Html');
/**
 * Before Render
 *
 * @return	void
 * @access	public
 */
	public function beforeRender($viewFile) {

		parent::beforeRender($viewFile);
		$this->savedUrl = '/files/uploads/';
		$this->savePath = WWW_ROOT . 'files' . DS . 'uploads' . DS;

	}
/**
 * リスト用のimgタグを出力する
 *
 * @param	array	$uploaderFile
 * @param	array	$options
 * @return	string	imgタグ
 */
	public function file ($uploaderFile,$options = array()) {

		if(isset($uploaderFile['UploaderFile'])) {
			$uploaderFile = $uploaderFile['UploaderFile'];
		}

		$imgUrl = $this->getFileUrl($uploaderFile['name']);

		$pathInfo = pathinfo($uploaderFile['name']);
		$ext = $pathInfo['extension'];
		$_options = array('alt'=>$uploaderFile['alt']);
		$options = Set::merge($_options,$options);

		if(in_array(strtolower($ext), array('gif','jpg','png'))) {
			if (isset($options['size'])) {
				$resizeName = $pathInfo['filename'] . '__' . $options['size'] . '.' . $ext;
				
				if(!empty($uploaderFile['publish_begin']) || !empty($uploaderFile['publish_end'])) {
					$savePath = $this->savePath . 'limited' . DS . $resizeName;
				} else {
					$savePath = $this->savePath . $resizeName;
				}
				if(file_exists($savePath)) {
					$imgUrl = $this->getFileUrl($resizeName);
					unset($options['size']);
				}
			}
			return $this->Html->image($imgUrl, $options);
		}else {
			$imgUrl = 'Uploader.icon_upload_file.png';
			return $this->Html->image($imgUrl, $options);
		}
		
	}

/**
 * ファイルが保存されているURLを取得する
 *
 * @param	string	$fileName
 * @return	string
 * @access	public
 */
	public function getFileUrl($fileName) {

		if($fileName) {
			return $this->savedUrl.$fileName;
		}else {
			return '';
		}

	}
	
/**
 * ダウンロードリンクを表示
 *
 * @param	array	$uploaderFile
 * @param	string	$linkText
 * @return	string
 */
	public function download($uploaderFile,$linkText='≫ ダウンロード') {
		if(isset($uploaderFile['UploaderFile'])) {
			$uploaderFile = $uploaderFile['UploaderFile'];
		}
		$fileUrl = $this->getFileUrl($uploaderFile['name']);
		// HtmlヘルパではスマートURLオフの場合に正常なURLが取得できないので、直接記述
		return '<a href="'.$fileUrl.'" target="_blank">'.$linkText.'</a>';
	}
	public function isLimitSetting($data) {
		
		if(!empty($data['UploaderFile'])) {
			$data = $data['UploaderFile'];
		}
		if(!empty($data['publish_begin']) || !empty($data['publish_end'])) {
			return true;
		} else {
			return false;
		}
		
	}
}

