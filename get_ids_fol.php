<?php
# unlimited time
set_time_limit(0);

# include the twitteroauth library
require_once('/home/twitteroauth/twitteroauth.php');

/**
* TWITTER OAUTH - GET ID USER FOLLOWERS
*/
class get_follower_ids{
	
	# assign variale
	protected $CONSUMER_KEY 	='ADD YOUR CONSUMER KEY';
	protected $CONSUMER_SECRET 	='ADD YOUR CONSUMER SCREET';
	protected $ACCESS_TOKEN		= 'ADD YOUR ACCESS TOKEN';
	protected $ACCESS_TOKEN_SECRET 	= 'ADD YOUR ACCESS TOKEN SECRET';
	protected $FORMAT_FILE		= 'txt';
	protected $DEST_FILE		= '/home/user/';
	protected $SCREEN_NAME		= 'SCREEN_NAME_TWITTER';

	/**
	* object to array
	*/
	public function object_to_array($data){
	    if (is_array($data) || is_object($data)){
		$result = array();
		foreach ($data as $key => $value){
		    $result[$key] = $this->object_to_array($value);
		}
		return $result;
	    }
	    return $data;
	}
	
	/**
	* get connection 
	*/
	public function get_connection(){
		$connection = new TwitterOAuth(
				$this->CONSUMER_KEY, 
	                      	$this->CONSUMER_SECRET, 
	                      	$this->ACCESS_TOKEN,
	                      	$this->ACCESS_TOKEN_SECRET);

		return $connection;
	}

	/**
	* create file
	*/
	public function create_file($ourFileName, $content){
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
		fwrite($ourFileHandle,$content);
		fclose($ourFileHandle);
	}

	/**
	* get data
	*/
	public function get_data($connection){
		$cursor = -1;
		$content = "";

		while($cursor != 0){
			$loc = 'followers/ids.json?screen_name='.$this->SCREEN_NAME.'&cursor='.$cursor.'&count=5000';
			echo "\n".$loc."\n";
	
			#data
			$data = (array)$connection->get($loc);

			#
			$size = sizeof($data['ids']);
			if($size > 0){
				echo "count: ".$size."\n";
				foreach($data['ids'] as $r){
					$content .= $this->SCREEN_NAME.",".$r."\n";
				}
			}else{
				break;
			}

			$cursor = $data['next_cursor_str'];
		}

		return $content;

	}

	/**
	* Main program
	*/
	public function main(){
		$FileName 	= $this->DEST_FILE.$this->SCREEN_NAME.".".$this->FORMAT_FILE;
		$connection 	= $this->get_connection();
		$content	= $this->get_data($connection);
		$file		= $this->create_file($FileName, $content);

	}
}

#call class
$class = new get_follower_ids();
$class->main();

?>
