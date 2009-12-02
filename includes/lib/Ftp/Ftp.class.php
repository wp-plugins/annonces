<?php
/**
 * sfFtp Library v1.0
 *
 * @author alexis cretinoir
 */
 class Ftp
{
	protected $ftp_server = null;
	protected $ftp_user_name = null;
	protected $ftp_user_pass = null;
	protected $source_file = null;
	protected $destination_file = null;

	public function __construct($ftp_server_new = null, $ftp_user_name_new = null, $ftp_user_pass_new = null)
	{
		if(!(is_null($ftp_server_new) and is_null($ftp_user_name_new) and is_null($ftp_user_pass_new))){
			if(is_string($ftp_server_new) and is_string($ftp_user_name_new) and is_string($ftp_user_pass_new)){
				$this->ftp_server =$ftp_server_new;
				$this->ftp_user_name =$ftp_user_name_new;
				$this->ftp_user_pass =$ftp_user_pass_new;
			}
		}
	}
	
	public function getFtpServer()
	{
		return $this->ftp_server;
	}

	public function setFtpServer( $ftp_server_new)
	{
		if(is_string($ftp_server_new)){
			$this->ftp_server = $ftp_server_new;
		}
	}
	public function getFtpUserName()
	{
		return $this->ftp_user_name;
	}

	public function setFtpUserName($ftp_user_name_new)
	{
		if(is_string($ftp_user_name_new)){
			$this->ftp_user_name = $ftp_user_name_new;
		}
	}	
	public function getFtpUserPass()
	{
		return $this->ftp_user_pass;
	}

	public function setFtpUserPass(	$ftp_user_pass_new)
	{
		if(is_string($ftp_user_pass_new)){
			$this->ftp_user_pass = $ftp_user_pass_new;
		}
	}	
	public function getSourceFile()
	{
		return $this->source_file;
	}

	public function setSourceFile( $source_file_new)
	{
		if(is_string($source_file_new)){
			$this->source_file = $source_file_new;
		}
	}	
	public function getDestinationFile()
	{
		return $this->destination_file;
	}

	public function setDestinationFile( $destination_file_new)
	{
		if(is_string($destination_file_new)){
			$this->destination_file = $destination_file_new;
		}
	}
	
	public function uploadToServer($source_file_new = null, $destination_file_new = null)
	{
		if(!is_null($source_file_new))
			{$this->setSourceFile($source_file_new);}
		
		if(!is_null($destination_file_new))
			{$this->setDestinationFile($destination_file_new);}
			
		if($conn_id = ftp_connect($this->getFtpServer()))
		{
			if($login_result = ftp_login($conn_id, $this->getFtpUserName(), $this->getFtpUserPass()))
			{
				if(!is_null($this->getDestinationFile()) and !is_null($this->getSourceFile()))
				{
					if($upload = ftp_put($conn_id, $this->getDestinationFile().'/'.basename($this->getSourceFile()), $this->getSourceFile(), FTP_BINARY))
					{
						return true;
					}else{
						ftp_quit($conn_id);
						return -4;
					}
				}else{
					ftp_quit($conn_id);
					return -3;
				}
			}
			else{
				ftp_quit($conn_id);
				return -2;
			}
		}else{
			return -1;
		}
	}
}