<?php
/**
 * 首页banner管理
 */
class BannerAction extends CommonAction {
	
	public function _filter(&$map) {
		if(!empty($_POST['keyword'])){
			$map['title'] = array('like', "%" . $_POST['keyword'] . "%");
			$map['_logic'] = 'or';
		}
	}
	/**
	 +----------------------------------------------------------
	 * 添加操作
	 +----------------------------------------------------------
	 */
	
	public function insert() {
		$name=$this->getActionName();
		$model = D ($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		 
		//替换URL空格
		$model->url=str_replace(array(' ','　'),array('',''),$model->url);
		//处理URL
		$model->url=prep_url($model->url);
		 
		//保存当前数据对象
		$list=$model->add();
		if ($list!==false) { //保存成功
			//上传图片
			if(!empty($_FILES['fileimg']['name'])){
				//导入上传类
				import('@.ORG.WeUploadFile');				
				$upload = new WeUploadFile();
				//只允许图片上传
				$upload->allow_type='image';
				if (!$upload->upload()) {
					//捕获上传异常
					$this->error($upload->getErrorMsg());
				} else {
					//取得成功上传的文件信息
					$file_info = $upload->getUploadFileInfo();
				}
				//dump($file_info);exit;
				//赋值当前表图片地址
				$data['uploads_id']=$file_info[0]['id'];
				$data['img']=$file_info[0]['fileurl'];
				$model->where("id='$list'")->save($data);
			}	
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('新增成功!');
		} else {
			//失败提示
			$this->error ('新增失败!');
		}
	}
	/**
	 +----------------------------------------------------------
	 * 编辑操作
	 +----------------------------------------------------------
	 */
	public function update() {
		$id = $_REQUEST ['id'];
		$name=$this->getActionName();
		$model = D ($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//替换URL空格
		$model->url=str_replace(array(' ','　'),array('',''),$model->url);
		//处理URL
		$model->url=prep_url($model->url);

		//上传图片
		if(!empty($_FILES['fileimg']['name'])){			
			//导入上传类
			import('@.ORG.WeUploadFile');
			$upload = new WeUploadFile();
			//只允许图片上传
			$upload->allow_type='image';
			if (!$upload->upload()) {
				//捕获上传异常
				$this->error($upload->getErrorMsg());
			} else {
				//取得成功上传的文件信息
				$file_info = $upload->getUploadFileInfo();
			}
			//删除修改前文件
			if($model->uploads_id){
				$model_uploads = D('Uploads');
				$model_uploads->deleteByID($model->uploads_id);
			}
			//赋值当前表图片地址
			$model->uploads_id=$file_info[0]['id'];
			$model->img=$file_info[0]['fileurl'];
		}
		// 更新数据
		$list=$model->save ();
		if (false !== $list) {
			//成功提示
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('编辑成功!');
		} else {
			//错误提示
			$this->error ('编辑失败!');
		}
	}
	/**
	 +----------------------------------------------------------
	 * 前置删除
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 */
	public function _before_foreverdelete(){
		//删除附件表上传图片
		$name=$this->getActionName();
		$model = D ($name);
		$id=$_REQUEST['id'];
		$data=$model->getByid($id);
		if($data['uploads_id']){
			$model_uploads=D('Uploads');
			$model_uploads->deleteByID($data['uploads_id']);
		}
	}
}

?>