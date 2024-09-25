if [ $# -ne 1 ];then
    echo "Usage $0 output-cert-path"
    exit 1
fi
composer exec CertificateDownloader.php -- -k b70b40c3f4425e73cd54e5bb536acffb -m 1517203851 -f /Users/wangxiaopei/cert/qingfan/1517203851_20240130_cert/apiclient_key.pem -s 26BF524214F0945A762ECA88DFC4F0166AB0D0C7 -o $1

# composer exec CertificateDownloader.php -- -k ${apiV3key} -m ${mchId} -f ${mchPrivateKeyFilePath} -s ${mchSerialNo} -o ${outputFilePath}
