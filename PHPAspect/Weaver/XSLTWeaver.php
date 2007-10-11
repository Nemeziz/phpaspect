<?php

define('LIBXML_OPTIONS', LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_DTDATTR | LIBXML_NOCDATA);

abstract class XSLTWeaver{

    const XSLT_TOWRITE = 'XSLT/toWrite.xsl';
    const XSLT_TOCLASS = 'XSLT/toClass.xsl';
    const XSLT_TOXSLT  = 'XSLT/toXSLT.xsl';
    
    private static function processFileIn($file, $xsl, $destination){
        file_put_contents($destination, $this->process($file, $xsl));
    }
    
    private static function processFile($file, $xsl){
        file_put_contents($file, $this->process($file, $xsl));
    }
    
    /*
     * @TODO: test the reference counting with get memory usage
     */
    private static function process($xml, $xsl){
        
        if($xml instanceof DOMDocument){
            //Do nothing
        }elseif(realpath($xml)){
            $xml = DOMDocument::load($xml);
        }elseif(is_string($xml)){
            $xml = DOMDOcument::loadXML($xml);
        }else{
            throw new InvalidParameterException($xml);
        }

        if($xsl instanceof DOMDocument){
            //Do nothing
        }elseif(realpath($xsl)){
            $xsl = DOMDocument::load($xsl);
        }elseif(is_string($xsl)){
            $xsl = DOMDocument::loadXML($xsl);
        }else{
            throw new InvalidParameterException($xsl);
        }
        
        $xsltProc = new XSLTProcessor();
        $xsltProc->importStyleSheet($xsl);
        return $xsltProc->transformToDoc($xml);
    }
}
?>