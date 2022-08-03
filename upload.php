<?php
 // download file from here - https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/getting-started_installation.html
 // Change 3 place - IAM-key , IAM-secret , Bucket-name .
 
	require './aws-autoloader.php';
	
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;

	// AWS Info
	$bucketName = 'Your_bucket_name';
	$IAM_KEY = 'Your_IAM_KEY';
	$IAM_SECRET = 'YOUR_IAM_SECRET';

	// Connect to AWS
	try {
		// You may need to change the region. It will say in the URL when the bucket is open
		// and on creation. us-east-2 is Ohio, us-east-1 is North Virgina
		echo 1;
		$s3 = S3Client::factory(
			array(
				'credentials' => array(
					'key' => $IAM_KEY,
					'secret' => $IAM_SECRET
				),
				'version' => 'latest',
				'region'  => 'ap-south-1'
			)
		);
		
	} catch (Exception $e) {
		
	die("Error: " . $e->getMessage());
	}

	


	// For this, I would generate a unqiue random string for the key name. But you can do whatever.
	$keyName = 'test_example/' . basename($_FILES["ftp"]['tmp_name']);   //ftp is file name at index.php
	$pathInS3 = 'https://s3.ap-south-1.amazonaws.com/' . $bucketName . '/' . $keyName;

	// Add it to S3
	try {
		
		if (!file_exists('/tmp/tmpfile')) {
		    echo 3;
			mkdir('/tmp/tmpfile');
		}
			
		$tempFilePath = '/tmp/tmpfile/' . basename($_FILES["ftp"]['name']);
	 
		$tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
		
		$fileContents = file_get_contents($_FILES["ftp"]['tmp_name']);
		$tempFile = file_put_contents($tempFilePath, $fileContents);

		$s3->putObject(
			array(
				'Bucket'=>$bucketName,
				'Key' =>  $keyName,
				'SourceFile' => $tempFilePath,
				'StorageClass' => 'REDUCED_REDUNDANCY',
				'ACL'   => 'public-read'
			)
		);

		

	} catch (S3Exception $e) {
		die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	}


	echo 'Done';

	
?>
