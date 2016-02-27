<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('pdf')) {
	function pdf($html, $filename = NULL, $destination = 'I', $dimension = NULL){
		
		$dimension=$dimension?$dimension:'A4-L';
		$mpdf = new mPDF('utf-8', $dimension);
		
		//$mpdf->allow_charset_conversion=true;
		//$mpdf->charset_in='iso-8859-1';
		
		//Exibir a pagina inteira no browser
		//$mpdf->SetDisplayMode('fullpage');
		
		//Cabeçalho: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
		//$mpdf->SetHeader('{DATE j/m/Y H:i}|{PAGENO}/{nb}|Texto no cabeçalho');
		 
		//Rodapé: Seta a data/hora completa de quando o PDF foi gerado + um texto no lado direito
		//$mpdf->SetFooter('{DATE j/m/Y H:i}|{PAGENO}/{nb}|Texto no rodapé');
		
		$mpdf->WriteHTML($html);
		
		// define um nome para o arquivo PDF
		if( ! $filename ){
			$filename = date("Y-m-d_his").'_impressao.pdf';
		}
		
		$mpdf->Output($filename, $destination);
	}
}
/* End of file mpdf_helper.php */
/* Location: ./application/helpers/mpdf_helper.php */