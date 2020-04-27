<?php
/**
 * WordPress SSH2 Filesystem.
 *
 * @package WordPress
 * @subpackage Filesystem
 */

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/phpseclib/');

if (!class_exists('Net_SFTP')) require_once('Net/SFTP.php');
if (!class_exists('Crypt_RSA')) require_once('Crypt/RSA.php');

/**
 * WordPress Filesystem Class for implementing SSH2.
 *
 * @since 2.7
 * @package WordPress
 * @subpackage Filesystem
 * @uses WP_Filesystem_Base Extends class
 */

class WP_Filesystem_SSH2 extends WP_Filesystem_Base {

	public $link = false;
	public $sftp_link = false;
	public $keys = false;
	public $password = false;
	public $errors = array();
	public $options = array();

	public function __construct($opt='') {
		$this->method = 'ssh2';
		$this->errors = new WP_Error();

		if ( !function_exists('stream_get_contents') ) {
			$this->errors->add('ssh2_php_requirement', __('We require the PHP5 function <code>stream_get_contents()</code>'));
			return false;
		}

		// Set defaults:
		if ( empty($opt['port']) )
			$this->options['port'] = 22;
		else
			$this->options['port'] = $opt['port'];

		if ( empty($opt['hostname']) )
			$this->errors->add('empty_hostname', __('SSH2 hostname is required'));
		else
			$this->options['hostname'] = $opt['hostname'];

		if ( ! empty($opt['base']) )
			$this->wp_base = $opt['base'];

		if ( !empty ($opt['private_key']) ) {
			$this->options['private_key'] = $opt['private_key'];

			$this->keys = true;
		} elseif ( empty ($opt['username']) ) {
			$this->errors->add('empty_username', __('SSH2 username is required'));
		}

		if ( !empty($opt['username']) )
			$this->options['username'] = $opt['username'];

		if ( empty ($opt['password']) ) {
			if ( !$this->keys )	//password can be blank if we are using keys
				$this->errors->add('empty_password', __('SSH2 password is required'));
		} else {
			$this->options['password'] = $opt['password'];

			$this->password = true;
		}
	}

	public function connect() {
	
		if (empty($this->options['hostname']) || empty($this->options['username'])) {
			$this->errors->add('auth', sprintf(__('No connection credentials available'), $this->options));
			return false;
		}
	
		$this->link = new Net_SFTP($this->options['hostname'], $this->options['port']);

		if ( !$this->keys ) {
			if ( ! $this->link->login($this->options['username'], $this->options['password']) ) {
				if ( $this->handle_connect_error() )
					return false;
				$this->errors->add('auth', sprintf(__('Username/Password incorrect for %s'), $this->options['username']));
				return false;
			}
		} else {
			$rsa = new Crypt_RSA();
			if ( $this->password ) {
				$rsa->setPassword($this->options['password']);
			}
			$rsa->loadKey($this->options['private_key']);
			if ( ! $this->link->login($this->options['username'], $rsa ) ) {
				if ( $this->handle_connect_error() )
					return false;
				$this->errors->add('auth', sprintf(__('Private key incorrect for %s'), $this->options['username']));
				$this->errors->add('auth', __('Make sure that the key you are using is an RSA key and not a DSA key'));
				return false;
			}
		}

		return true;
	}

	public function handle_connect_error() {
		if ( ! $this->link->isConnected() ) {
			$this->errors->add('connect', sprintf(__('Failed to connect to SSH2 Server %1$s:%2$s'), $this->options['hostname'], $this->options['port']));
			$this->errors->add('connect2', __('If SELinux is installed check to make sure that <code>httpd_can_network_connect</code> is set to 1'));
			return true;
		}

		return false;
	}

	public function run_command( $command, $returnbool = false) {

		if ( ! $this->link )
			return false;

		$data = $this->link->exec($command);

		if ( $returnbool )
			return ( $data === false ) ? false : '' != trim($data);
		else
			return $data;
	}

	public function get_contents($file, $type = '', $resumepos = 0 ) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return $this->link->get($file);
	}

	public function get_contents_array($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$lines = preg_split('#(\r\n|\r|\n)#', $this->link->get($file), -1, PREG_SPLIT_DELIM_CAPTURE);
		$newLines = array();
		for ($i = 0; $i < count($lines); $i+= 2)
			$newLines[] = $lines[$i] . $lines[$i + 1];
		return $newLines;
	}

	public function put_contents($file, $contents, $mode = false ) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$ret = $this->link->put($file, $contents);

		$this->chmod($file, $mode);

		return false !== $ret;
	}

	public function cwd() {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$cwd = $this->run_command('pwd');
		if ( $cwd )
			$cwd = trailingslashit($cwd);
		return $cwd;
	}

	public function chdir($dir) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$this->list->chdir($dir);
		return $this->run_command('cd ' . $dir, true);
	}

	public function chgrp($file, $group, $recursive = false ) {
		if ( ! $this->exists($file) )
			return false;
		if ( ! $recursive || ! $this->is_dir($file) )
			return $this->run_command(sprintf('chgrp %o %s', $mode, escapeshellarg($file)), true);
		return $this->run_command(sprintf('chgrp -R %o %s', $mode, escapeshellarg($file)), true);
	}

	public function chmod($file, $mode = false, $recursive = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return $mode === false ? false : $this->link->chmod($mode, $file, $recursive);
	}

	public function chown($file, $owner, $recursive = false ) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ( ! $this->exists($file) )
			return false;
		if ( ! $recursive || ! $this->is_dir($file) )
			return $this->run_command(sprintf('chown %o %s', $mode, escapeshellarg($file)), true);
		return $this->run_command(sprintf('chown -R %o %s', $mode, escapeshellarg($file)), true);
	}

	public function owner($file, $owneruid = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ($owneruid === false) {
			$result = $this->link->stat($file);
			$owneruid = $result['uid'];
		}

		if ( ! $owneruid )
			return false;
		if ( ! function_exists('posix_getpwuid') )
			return $owneruid;
		$ownerarray = posix_getpwuid($owneruid);
		return $ownerarray['name'];
	}

	public function getchmod($file) {
		$result = $this->link->stat($file);

		return substr(decoct($result['permissions']),3);
	}

	public function group($file, $gid = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ($gid === false) {
			$result = $this->link->stat($file);
			$gid = $result['gid'];
		}

		if ( ! $gid )
			return false;
		if ( ! function_exists('posix_getgrgid') )
			return $gid;
		$grouparray = posix_getgrgid($gid);
		return $grouparray['name'];
	}

	public function copy($source, $destination, $overwrite = false, $mode = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ( ! $overwrite && $this->exists($destination) )
			return false;
		$content = $this->get_contents($source);
		if ( false === $content)
			return false;
		return $this->put_contents($destination, $content, $mode);
	}

	public function move($source, $destination, $overwrite = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return $this->link->rename($source, $destination);
	}

	public function delete($file, $recursive = false, $type = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ( 'f' == $type || $this->is_file($file) )
			return $this->link->delete($file);
		if ( ! $recursive )
			return $this->link->rmdir($file);
		return $this->link->delete($file, $recursive);
	}

	public function exists($file) {
		return $this->link->stat($file) !== false;
	}

	public function is_file($file) {
		$result = $this->link->stat($file);
		return $result['type'] == NET_SFTP_TYPE_REGULAR;
	}

	public function is_dir($path) {
		$result = $this->link->stat($path);
		return $result['type'] == NET_SFTP_TYPE_DIRECTORY;
	}

	public function is_readable($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return true;

		return is_readable('ssh2.sftp://' . $this->sftp_link . '/' . $file);
	}

	public function is_writable($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return true;

		return is_writable('ssh2.sftp://' . $this->sftp_link . '/' . $file);
	}

	public function atime($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$result = $this->link->stat($file);
		return $result['atime'];
	}

	public function mtime($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$result = $this->link->stat($file);
		return $result['mtime'];
	}

	public function size($file) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$result = $this->link->stat($file);
		return $result['size'];
	}

	public function touch($file, $time = 0, $atime = 0) {
		//Not implmented.
	}

	public function mkdir($path, $chmod = false, $chown = false, $chgrp = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		$path = untrailingslashit($path);
		if ( ! $chmod )
			$chmod = FS_CHMOD_DIR;
		//if ( ! ssh2_sftp_mkdir($this->sftp_link, $path, $chmod, true) )
		//	return false;
		if ( ! $this->link->mkdir($path) && $this->link->chmod($chmod, $path) )
			return false;
		if ( $chown )
			$this->chown($path, $chown);
		if ( $chgrp )
			$this->chgrp($path, $chgrp);
		return true;
	}

	public function rmdir($path, $recursive = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		return $this->delete($path, $recursive);
	}

	public function dirlist($path, $include_hidden = true, $recursive = false) {
		if (!is_a($this->link, 'Net_SFTP')) return false;
		if ( $this->is_file($path) ) {
			$limit_file = basename($path);
			$path = dirname($path);
		} else {
			$limit_file = false;
		}

		if ( ! $this->is_dir($path) )
			return false;

		$ret = array();
		$entries = $this->link->rawlist($path);

		if ( $entries === false )
			return false;

		foreach ($entries as $name => $entry) {
			$struc = array();
			$struc['name'] = $name;

			if ( '.' == $struc['name'] || '..' == $struc['name'] )
				continue; //Do not care about these folders.

			if ( ! $include_hidden && '.' == $struc['name'][0] )
				continue;

			if ( $limit_file && $struc['name'] != $limit_file )
				continue;

			$struc['perms'] 	= $entry['permissions'];
			$struc['permsn']	= $this->getnumchmodfromh($struc['perms']);
			$struc['number'] 	= false;
			$struc['owner']    	= $this->owner($path.'/'.$name, $entry['uid']);
			$struc['group']    	= $this->group($path.'/'.$name, $entry['gid']);
			$struc['size']    	= $entry['size'];//$this->size($path.'/'.$entry);
			$struc['lastmodunix']= $entry['mtime'];//$this->mtime($path.'/'.$entry);
			$struc['lastmod']   = date('M j',$struc['lastmodunix']);
			$struc['time']    	= date('h:i:s',$struc['lastmodunix']);
			$struc['type']		= $entry['type'] == NET_SFTP_TYPE_DIRECTORY ? 'd' : 'f';

			if ( 'd' == $struc['type'] ) {
				if ( $recursive )
					$struc['files'] = $this->dirlist($path . '/' . $struc['name'], $include_hidden, $recursive);
				else
					$struc['files'] = array();
			}

			$ret[ $struc['name'] ] = $struc;
		}
		return $ret;
	}
}
