<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class BankModel extends CI_Model  
{
	function select_query($query)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		return $db_bank->query($query);	
	}

	function select_row($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($where!="")
		{
			$db_bank->where($where);
		}
		return $db_bank->get($tbl)->row();
	}

	public function insert_statment($table, $data) {
		// Check for duplicate customer_reference
		$db_bank = $this->load->database('bank_db', TRUE);
		$db_bank->where('customer_reference', $data['customer_reference']);
		$query = $db_bank->get($table);
	
		if ($query->num_rows() > 0) {
			// Customer reference already exists
			return false; // or you can return a custom error message
		} else {
			// Insert the data
			return $db_bank->insert($table, $data);
		}
	}

	
	function insert_fun($tbl,$dt)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->insert($tbl,$dt))
		{
			return $db_bank->insert_id();
		}
		else
		{
			return false;
		}
	}
	function edit_fun($tbl,$dt,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function delete_fun($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->delete($tbl,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function select_fun_limit($tbl,$where,$get_limit='',$order_by='')
	{
		$db_bank = $this->load->database('db_bank', TRUE);
		if(!empty($where))
		{
			$db_bank->where($where);
		}
		if(!empty($order_by))
		{
			$db_bank->order_by($order_by[0],$order_by[1]);
		}
		if(!empty($get_limit))
		{
			$db_bank->limit($get_limit[0],$get_limit[1]);
		}
		return $db_bank->get($tbl);	
	}

	public function statment_excel_file($download_type,$start_date,$end_date)
	{			
		/*$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		ob_clean();*/

		// 📂 नया Spreadsheet बनाएँ
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('B1','Account Statement Inquiry');
		$sheet->setCellValue('A3','Search Criteria:');
		$sheet->setCellValue('A5',"Account:'Equals'");
		$sheet->setCellValue('A6',"Branch:'Equals'");
		$sheet->setCellValue('A7',"Customer:'Equals'");
		$sheet->setCellValue('A8',"Cheques: ");
		$sheet->setCellValue('A9',"Statement Date Range:");

		$sheet->mergeCells('B1:E1'); 

		$sheet->setCellValue('A11','Account Number');
		$sheet->setCellValue('B11','Branch Number');
		$sheet->setCellValue('C11','Statement Date');
		$sheet->setCellValue('D11','Closing Ledger Balance');
		$sheet->setCellValue('E11','Calculated Balances');
		$sheet->setCellValue('F11','Amount');
		$sheet->setCellValue('G11','Entry Date');
		$sheet->setCellValue('H11','Value Date');
		$sheet->setCellValue('I11','Bank Reference');
		$sheet->setCellValue('J11','Customer Reference');
		$sheet->setCellValue('K11','Narrative');
		$sheet->setCellValue('L11','Transaction Description');
		$sheet->setCellValue('M11','IBAN Number');
		$sheet->setCellValue('N11','Chemist Id');
		$sheet->setCellValue('O11','Invoice');
		$sheet->setCellValue('P11','Find By');
		
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(20);
		$sheet->getColumnDimension('F')->setWidth(20);
		$sheet->getColumnDimension('G')->setWidth(20);
		$sheet->getColumnDimension('H')->setWidth(20);
		$sheet->getColumnDimension('I')->setWidth(20);
		$sheet->getColumnDimension('J')->setWidth(20);
		$sheet->getColumnDimension('K')->setWidth(20);
		$sheet->getColumnDimension('L')->setWidth(20);
		$sheet->getColumnDimension('M')->setWidth(20);
		$sheet->getColumnDimension('N')->setWidth(20);
		$sheet->getColumnDimension('O')->setWidth(20);
		$sheet->getColumnDimension('P')->setWidth(20);
		
		/*$sheet->getStyle('A1:P1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		$sheet->getStyle('A1:P1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('ccffff');
		
		$BStyle = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		);
		$sheet->getStyle('A11:P11')->applyFromArray($BStyle);*/
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date'");
		$result = $query->result();
		$rowCount = 12;
		$fileok=0;
		foreach($result as $row)
		{			
			$sheet->SetCellValue('A'.$rowCount,$row->account_no);
			$sheet->SetCellValue('B'.$rowCount,$row->branch_no);
			$sheet->SetCellValue('C'.$rowCount,$row->statment_date);
			$sheet->SetCellValue('D'.$rowCount,$row->closing_ledger_balance);
			$sheet->SetCellValue('E'.$rowCount,$row->calculated_balances);
			//$sheet->SetCellValue('F'.$rowCount,(int)$row->amount);
			$sheet->SetCellValue('F'.$rowCount,$row->amount);
			$sheet->SetCellValue('G'.$rowCount,$row->enter_date);
			$sheet->SetCellValue('H'.$rowCount,$row->date);
			$sheet->SetCellValue('I'.$rowCount,$row->bank_reference);
			$sheet->SetCellValue('J'.$rowCount,$row->customer_reference);
			$sheet->SetCellValue('K'.$rowCount,$row->narrative);
			$sheet->SetCellValue('L'.$rowCount,$row->transaction_description);
			$sheet->SetCellValue('M'.$rowCount,$row->iban_number);
			$sheet->SetCellValue('N'.$rowCount,$row->chemist_id);
			$sheet->SetCellValue('O'.$rowCount,$row->invoice_number);
			//$sheet->SetCellValue('P'.$rowCount,$row->done_find_by);
			
			//$sheet->getStyle('A'.$rowCount.':P'.$rowCount)->applyFromArray($BStyle);
			$rowCount++;
		}
		
		$name = "statment";
		if($download_type=="direct_download")
		{
			$file_name = $name."-".$start_date."-to-".$end_date.".xls";
			
			// 📂 Writer तैयार करें
			$writer = IOFactory::createWriter($spreadsheet, 'Xls');

			// 📂 Header सेट करें ताकि फाइल डाउनलोड हो
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$file_name.'"');
			header('Cache-Control: max-age=0');

			// 📂 फ़ाइल को ब्राउज़र में आउटपुट करें
			$writer->save('php://output');
			exit;
		}
		
		if($download_type=="cronjob_download")
		{
			if($fileok==1)
			{
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
				$file_name = "test_folder/".$name.".xls";
				$objWriter->save($file_name);
				
				$file_name2 = "test_folder/xx".$name.".xls";
				$objWriter->save($file_name2);
				
				$x[0] = $file_name;
				$x[1] = $invoice_message_body;
 				return $x;
			}
			else
			{
				$file_name = "";
				return $file_name;
			}
		}
	}

	public function statment_excel_file1($download_type,$start_date,$end_date)
	{	
		error_reporting(0);
		
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		ob_clean();

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B1','Account Statement Inquiry')
		->setCellValue('A3','Search Criteria:')
		->setCellValue('A5',"Account:'Equals'")
		->setCellValue('A6',"Branch:'Equals'")
		->setCellValue('A7',"Customer:'Equals'")
		->setCellValue('A8',"Cheques: ")
		->setCellValue('A9',"Statement Date Range:");

		$objPHPExcel->getActiveSheet()->mergeCells('B1:E1'); 

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A11','Account Number')
		->setCellValue('B11','Branch Number')
		->setCellValue('C11','Statement Date')
		->setCellValue('D11','Closing Ledger Balance')
		->setCellValue('E11','Calculated Balances')
		->setCellValue('F11','Amount')
		->setCellValue('G11','Entry Date')
		->setCellValue('H11','Value Date')
		->setCellValue('I11','Bank Reference')
		->setCellValue('J11','Customer Reference')
		->setCellValue('K11','Narrative')
		->setCellValue('L11','Transaction Description')
		->setCellValue('M11','IBAN Number')
		->setCellValue('N11','Chemist Id')
		->setCellValue('O11','Invoice')
		->setCellValue('P11','Find By');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		$objPHPExcel->getActiveSheet()
        ->getStyle('A1:P1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('ccffff');
		
		$BStyle = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A11:P11')->applyFromArray($BStyle);
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_invoice as done_invoice,p.final_find_by as done_find_by from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date'");
		$result = $query->result();
		$rowCount = 12;
		$fileok=0;
		foreach($result as $row)
		{			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$row->account_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$row->branch_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$row->statment_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$row->closing_ledger_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$row->calculated_balances);
			//$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,(int)$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$row->enter_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$row->date);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$row->bank_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$row->customer_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$row->narrative);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$row->transaction_description);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$row->iban_number);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$row->chemist_id);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$row->done_invoice);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$row->done_find_by);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':P'.$rowCount)->applyFromArray($BStyle);
			$rowCount++;
		}
		
		$name = "statment";
		if($download_type=="direct_download")
		{
			$file_name = $name."-".$start_date."-to-".$end_date.".xls";
			
			//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			/*$objWriter->save('uploads_sales/kapilkifile.xls');*/
			
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename='.$file_name);
			header('Cache-Control: max-age=0');
			ob_start();
			$objWriter->save('php://output');
			$data = ob_get_contents();
		}
		
		if($download_type=="cronjob_download")
		{
			if($fileok==1)
			{
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
				$file_name = "test_folder/".$name.".xls";
				$objWriter->save($file_name);
				
				$file_name2 = "test_folder/xx".$name.".xls";
				$objWriter->save($file_name2);
				
				$x[0] = $file_name;
				$x[1] = $invoice_message_body;
 				return $x;
			}
			else
			{
				$file_name = "";
				return $file_name;
			}
		}
	}

	public function statment_excel_file2($download_type,$start_date,$end_date)
	{	
		error_reporting(0);
		
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		ob_clean();

		/*$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B1','Account Statement Inquiry')
		->setCellValue('A3','Search Criteria:')
		->setCellValue('A5',"Account:'Equals'")
		->setCellValue('A6',"Branch:'Equals'")
		->setCellValue('A7',"Customer:'Equals'")
		->setCellValue('A8',"Cheques: ")
		->setCellValue('A9',"Statement Date Range:");

		$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');*/ 

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','Value Date')
		->setCellValue('B1','Account Number')
		->setCellValue('C1','Account Name')
		->setCellValue('D1','Beneficiary / Remitter')
		->setCellValue('E1','Currency')
		->setCellValue('F1','Amount')
		->setCellValue('G1','Customer Reference')
		->setCellValue('H1','Branch Name')
		->setCellValue('I1','Statment Date')
		->setCellValue('J1','Type')
		->setCellValue('K1','Entry Date')
		->setCellValue('L1','Description')
		->setCellValue('M1','Bank Reference')
		->setCellValue('N1','Narrative')
		->setCellValue('O1','Chemist Id')
		->setCellValue('P1','Invoice')
		->setCellValue('Q1','Find By');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		$objPHPExcel->getActiveSheet()
        ->getStyle('A1:Q1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('ccffff');
		
		$BStyle = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A11:Q11')->applyFromArray($BStyle);

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_invoice as done_invoice,p.final_find_by as done_find_by from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date'");
		$result = $query->result();
		$rowCount = 2;
		$fileok=0;
		foreach($result as $row)
		{	
			$value_date1 = DateTime::createFromFormat('Y-m-d', $row->date)->format('d/m/Y');
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$value_date1);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$row->account_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$row->branch_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$row->beneficiary_remitter);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$row->currency);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$row->customer_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$row->branch_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$row->statment_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$row->type);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$row->enter_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$row->transaction_description);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$row->bank_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$row->narrative);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$row->chemist_id);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$row->done_invoice);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$row->done_find_by);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':Q'.$rowCount)->applyFromArray($BStyle);
			$rowCount++;
		}
		
		$name = "statment";
		if($download_type=="direct_download")
		{
			$file_name = $name."-".$start_date."-to-".$end_date.".xls";
			
			//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			/*$objWriter->save('uploads_sales/kapilkifile.xls');*/
			
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename='.$file_name);
			header('Cache-Control: max-age=0');
			ob_start();
			$objWriter->save('php://output');
			$data = ob_get_contents();
		}
		
		if($download_type=="cronjob_download")
		{
			if($fileok==1)
			{
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
				$file_name = "test_folder/".$name.".xls";
				$objWriter->save($file_name);
				
				$file_name2 = "test_folder/xx".$name.".xls";
				$objWriter->save($file_name2);
				
				$x[0] = $file_name;
				$x[1] = $invoice_message_body;
 				return $x;
			}
			else
			{
				$file_name = "";
				return $file_name;
			}
		}
	}
	
	public function statment_excel_file3($download_type,$start_date,$end_date)
	{	
		error_reporting(0);
		
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		ob_clean();

		/*$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B1','Account Statement Inquiry')
		->setCellValue('A3','Search Criteria:')
		->setCellValue('A5',"Account:'Equals'")
		->setCellValue('A6',"Branch:'Equals'")
		->setCellValue('A7',"Customer:'Equals'")
		->setCellValue('A8',"Cheques: ")
		->setCellValue('A9',"Statement Date Range:");

		$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');*/ 

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','Value Date')
		->setCellValue('B1','Statment Date')
		->setCellValue('C1','Currency')
		->setCellValue('D1','Amount')
		->setCellValue('E1','Beneficiary / Remitter')
		->setCellValue('F1','Customer Reference')
		->setCellValue('G1','Type')
		->setCellValue('H1','Branch Number')
		->setCellValue('I1','Branch Name')
		->setCellValue('J1','Entry Date')
		->setCellValue('K1','Bank Reference')
		->setCellValue('L1','Description')
		->setCellValue('M1','Narrative')
		->setCellValue('N1','Payment Details')
		->setCellValue('O1','Chemist Id')
		->setCellValue('P1','Invoice')
		->setCellValue('Q1','Find By');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		$objPHPExcel->getActiveSheet()
        ->getStyle('A1:Q1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('ccffff');
		
		$BStyle = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A11:Q11')->applyFromArray($BStyle);

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.final_invoice as done_invoice,p.final_find_by as done_find_by from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date'");
		$result = $query->result();
		$rowCount = 2;
		$fileok=0;
		foreach($result as $row)
		{	
			$date = date('m/d/Y', strtotime($row->date));
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$date);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$row->statment_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$row->currency);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$row->beneficiary_remitter);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$row->customer_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$row->type);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$row->branch_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$row->branch_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$row->enter_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$row->bank_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$row->transaction_description);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$row->narrative);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$row->payment_details);
			$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$row->chemist_id);
			$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$row->done_invoice);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$row->done_find_by);

			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':Q'.$rowCount)->applyFromArray($BStyle);
			$rowCount++;
		}
		
		$name = "statment";
		if($download_type=="direct_download")
		{
			$file_name = $name."-".$start_date."-to-".$end_date.".xls";
			
			//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			/*$objWriter->save('uploads_sales/kapilkifile.xls');*/
			
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename='.$file_name);
			header('Cache-Control: max-age=0');
			ob_start();
			$objWriter->save('php://output');
			$data = ob_get_contents();
		}
		
		if($download_type=="cronjob_download")
		{
			if($fileok==1)
			{
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
				$file_name = "test_folder/".$name.".xls";
				$objWriter->save($file_name);
				
				$file_name2 = "test_folder/xx".$name.".xls";
				$objWriter->save($file_name2);
				
				$x[0] = $file_name;
				$x[1] = $invoice_message_body;
 				return $x;
			}
			else
			{
				$file_name = "";
				return $file_name;
			}
		}
	}
}	