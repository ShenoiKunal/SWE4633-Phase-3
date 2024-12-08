import json
import boto3
import base64

def lambda_handler(event, context):
    # S3 configuration
    bucket_name = 'swe4633-fileupload'
    region = 'us-east-1'
    
    # Get the file from the event
    file_content = base64.b64decode(event['body'])
    file_name = event['headers']['file-name']
    
    # Initialize S3 client
    s3_client = boto3.client('s3', region_name=region)
    
    # Upload the file to S3
    s3_client.put_object(Bucket=bucket_name, Key=file_name, Body=file_content)
    
    return {
        'statusCode': 200,
        'body': json.dumps('File uploaded successfully')
    }