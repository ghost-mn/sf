<?php


namespace Sf;

use Sf\Base\AbstractAPI;
class Api extends AbstractAPI
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $checkWord;

    private $url;

    const XML_HEAD = '
    <Request service="%s" lang="zh-CN">
        <Head>
            <AccessCode>%s</AccessCode>
            <Checkword>%s</Checkword>
        </Head>
        <Body>
        %s
        </Body>
    </Request>';

    public function __construct(string $accessToken, string $checkWord, string $url = 'http://bsp.sit.Sf-express.com:8080/bsp-wms/OmsCommons')
    {
        $this->accessToken = $accessToken;
        $this->checkWord = $checkWord;
        $this->url = $url;
    }

    public function signature($xml)
    {
        $logistics_interface = urlencode($xml);
        $data_digest = $xml . $this->checkWord;
        //$md5 = md5(mb_convert_encoding($data_digest, 'UTF-8', mb_detect_encoding($data_digest)), true);
        $data_digest = urlencode(base64_encode(md5($data_digest)));
        return "logistics_interface=" . $logistics_interface . "&data_digest=" . $data_digest;
    }

    public function request(string $service, array $params)
    {
        $xml = $this->getPostXml($service, $params);
        $sign = $this->signature($xml);
        $post_data="xml=$xml&" . $sign;
        $response = $this->postXml($this->url, $post_data);
        $result = json_decode(strval($response->getBody()), true);
        return $result;
    }

    public function postXml($url, $data)
    {
        try{
            header("Content-type: text/html; charset=utf-8");
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getPostXml(string $service, array $params)
    {
        $xml = $this->paramsToString($params);
        $xml = sprintf(self::XML_HEAD, $service, $this->accessToken, $this->checkWord, $xml);
        return str_replace(["\n", "\t", "\r", " "], '', $xml);
    }

    protected function paramsToString($params = [], $root = true)
    {
        $str = "";
        foreach($params as $key => $val){
            if(is_array($val)){
                $child = $this->paramsToString($val, false);
                $str .= "<$key>$child</$key>";
            }else{
                $str .= "<$key>$val</$key>";
            }
        }
        return $str;
    }
}