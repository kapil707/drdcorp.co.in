<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

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
		
		$sheet->getStyle('A1:P1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));

		// 📂 Header Background Color सेट करें (A1 से P1)
		$sheet->getStyle('A1:P1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFFF');

		// 📂 Borders सेट करें (A11 से P11)
		$borderStyle = [
					'borders' => [
					'allBorders' => [
					'borderStyle' => Border::BORDER_THIN
				]
			]
		];
		$sheet->getStyle('A11:P11')->applyFromArray($borderStyle);
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date' order by s.id asc");
		$result = $query->result();
		$rowCount = 12;
		$fileok=0;
		foreach($result as $row)
		{
			$gstvNo = "";
			$invoice = $row->invoice_number; 
			if(!empty($invoice)){
				$parts = explode("||", $invoice);
				foreach($parts as $invoice) {
					preg_match('/GstvNo:([\w-]+)/', $invoice, $matches);
					if(!empty($matches[1])){
						$gstvNo.= $matches[1].',';
					}
				}
				$gstvNo = substr($gstvNo, 0, -2);
			}

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
			$sheet->SetCellValue('O'.$rowCount,$gstvNo);
			//$sheet->SetCellValue('P'.$rowCount,$row->done_find_by);
			
			$sheet->getStyle('A'.$rowCount.':P'.$rowCount)->applyFromArray($borderStyle);
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
			
		}
	}

	public function statment_excel_file1($download_type,$start_date,$end_date)
	{	
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
		
		$sheet->getStyle('A1:P1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		// 📂 Header Background Color सेट करें (A1 से P1)
		$sheet->getStyle('A1:P1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFFF');

		// 📂 Borders सेट करें (A11 से P11)
		$borderStyle = [
					'borders' => [
					'allBorders' => [
					'borderStyle' => Border::BORDER_THIN
				]
			]
		];
		$sheet->getStyle('A11:P11')->applyFromArray($borderStyle);
		
		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date' order by s.id asc");
		$result = $query->result();
		$rowCount = 12;
		$fileok=0;
		foreach($result as $row)
		{	
			$gstvNo = "";
			$invoice = $row->invoice_number; 
			$parts = explode("||", $invoice);
			foreach($parts as $invoice) {
				preg_match('/GstvNo:([\w-]+)/', $invoice, $matches);
				$gstvNo.= $matches[1].',';
			}
			$gstvNo = substr($gstvNo, 0, -2);

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
			$sheet->SetCellValue('O'.$rowCount,$row->gstvNo);
			//$sheet->SetCellValue('P'.$rowCount,$row->done_find_by);
			
			$sheet->getStyle('A'.$rowCount.':P'.$rowCount)->applyFromArray($borderStyle);
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
			
		}
	}

	public function statment_excel_file2($download_type,$start_date,$end_date)
	{	
		// 📂 नया Spreadsheet बनाएँ
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1','Value Date');
		$sheet->etCellValue('B1','Account Number');
		$sheet->setCellValue('C1','Account Name');
		$sheet->setCellValue('D1','Beneficiary / Remitter');
		$sheet->setCellValue('E1','Currency');
		$sheet->setCellValue('F1','Amount');
		$sheet->setCellValue('G1','Customer Reference');
		$sheet->setCellValue('H1','Branch Name');
		$sheet->setCellValue('I1','Statment Date');
		$sheet->setCellValue('J1','Type');
		$sheet->setCellValue('K1','Entry Date');
		$sheet->setCellValue('L1','Description');
		$sheet->setCellValue('M1','Bank Reference');
		$sheet->setCellValue('N1','Narrative');
		$sheet->setCellValue('O1','Chemist Id');
		$sheet->setCellValue('P1','Invoice');
		$sheet->setCellValue('Q1','Find By');
		
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
		$sheet->getColumnDimension('Q')->setWidth(20);
		
		$sheet->getStyle('A1:Q1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		// 📂 Header Background Color सेट करें (A1 से P1)
		$sheet->getStyle('A1:Q1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFFF');

		// 📂 Borders सेट करें (A11 से P11)
		$borderStyle = [
					'borders' => [
					'allBorders' => [
					'borderStyle' => Border::BORDER_THIN
				]
			]
		];
		$sheet->getStyle('A11:Q11')->applyFromArray($borderStyle);

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date' order by s.id asc");
		$result = $query->result();
		$rowCount = 2;
		$fileok=0;
		foreach($result as $row)
		{	
			$gstvNo = "";
			$invoice = $row->invoice_number; 
			$parts = explode("||", $invoice);
			foreach($parts as $invoice) {
				preg_match('/GstvNo:([\w-]+)/', $invoice, $matches);
				$gstvNo.= $matches[1].',';
			}
			$gstvNo = substr($gstvNo, 0, -2);

			$value_date1 = DateTime::createFromFormat('Y-m-d', $row->date)->format('d/m/Y');
			$sheet->SetCellValue('A'.$rowCount,$value_date1);
			$sheet->SetCellValue('B'.$rowCount,$row->account_no);
			$sheet->SetCellValue('C'.$rowCount,$row->branch_no);
			$sheet->SetCellValue('D'.$rowCount,$row->beneficiary_remitter);
			$sheet->SetCellValue('E'.$rowCount,$row->currency);
			$sheet->SetCellValue('F'.$rowCount,$row->amount);
			$sheet->SetCellValue('G'.$rowCount,$row->customer_reference);
			$sheet->SetCellValue('H'.$rowCount,$row->branch_name);
			$sheet->SetCellValue('I'.$rowCount,$row->statment_date);
			$sheet->SetCellValue('J'.$rowCount,$row->type);
			$sheet->SetCellValue('K'.$rowCount,$row->enter_date);
			$sheet->SetCellValue('L'.$rowCount,$row->transaction_description);
			$sheet->SetCellValue('M'.$rowCount,$row->bank_reference);
			$sheet->SetCellValue('N'.$rowCount,$row->narrative);
			$sheet->SetCellValue('O'.$rowCount,$row->chemist_id);
			$sheet->SetCellValue('P'.$rowCount,$gstvNo);
			//$sheet->SetCellValue('Q'.$rowCount,$row->done_find_by);

			$sheet->getStyle('A'.$rowCount.':Q'.$rowCount)->applyFromArray($borderStyle);
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
			
		}
	}
	
	public function statment_excel_file3($download_type,$start_date,$end_date)
	{	
		// 📂 नया Spreadsheet बनाएँ
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1','Value Date');
		$sheet->setCellValue('B1','Statment Date');
		$sheet->setCellValue('C1','Currency');
		$sheet->setCellValue('D1','Amount');
		$sheet->setCellValue('E1','Beneficiary / Remitter');
		$sheet->setCellValue('F1','Customer Reference');
		$sheet->setCellValue('G1','Type');
		$sheet->setCellValue('H1','Branch Number');
		$sheet->setCellValue('I1','Branch Name');
		$sheet->setCellValue('J1','Entry Date');
		$sheet->setCellValue('K1','Bank Reference');
		$sheet->setCellValue('L1','Description');
		$sheet->setCellValue('M1','Narrative');
		$sheet->setCellValue('N1','Payment Details');
		$sheet->setCellValue('O1','Chemist Id');
		$sheet->setCellValue('P1','Invoice');
		$sheet->setCellValue('Q1','Find By');
		
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
		$sheet->getColumnDimension('Q')->setWidth(20);
		
		$sheet->getStyle('A1:Q1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		// 📂 Header Background Color सेट करें (A1 से P1)
		$sheet->getStyle('A1:Q1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFFF');

		// 📂 Borders सेट करें (A11 से P11)
		$borderStyle = [
					'borders' => [
					'allBorders' => [
					'borderStyle' => Border::BORDER_THIN
				]
			]
		];
		$sheet->getStyle('A11:Q11')->applyFromArray($borderStyle);

		$query = $this->BankModel->select_query("SELECT s.*,p.final_chemist as chemist_id,p.invoice_text as invoice_number from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where s.date BETWEEN '$start_date' AND '$end_date' order by s.id asc");
		$result = $query->result();
		$rowCount = 2;
		$fileok=0;
		foreach($result as $row)
		{	
			$gstvNo = "";
			$invoice = $row->invoice_number; 
			$parts = explode("||", $invoice);
			foreach($parts as $invoice) {
				preg_match('/GstvNo:([\w-]+)/', $invoice, $matches);
				$gstvNo.= $matches[1].',';
			}
			$gstvNo = substr($gstvNo, 0, -2);

			$date = date('m/d/Y', strtotime($row->date));
			$sheet->SetCellValue('A'.$rowCount,$date);
			$sheet->SetCellValue('B'.$rowCount,$row->statment_date);
			$sheet->SetCellValue('C'.$rowCount,$row->currency);
			$sheet->SetCellValue('D'.$rowCount,$row->amount);
			$sheet->SetCellValue('E'.$rowCount,$row->beneficiary_remitter);
			$sheet->SetCellValue('F'.$rowCount,$row->customer_reference);
			$sheet->SetCellValue('G'.$rowCount,$row->type);
			$sheet->SetCellValue('H'.$rowCount,$row->branch_no);
			$sheet->SetCellValue('I'.$rowCount,$row->branch_name);
			$sheet->SetCellValue('J'.$rowCount,$row->enter_date);
			$sheet->SetCellValue('K'.$rowCount,$row->bank_reference);
			$sheet->SetCellValue('L'.$rowCount,$row->transaction_description);
			$sheet->SetCellValue('M'.$rowCount,$row->narrative);
			$sheet->SetCellValue('N'.$rowCount,$row->payment_details);
			$sheet->SetCellValue('O'.$rowCount,$row->chemist_id);
			$sheet->SetCellValue('P'.$rowCount,$gstvNo);
			//$sheet->SetCellValue('Q'.$rowCount,$row->done_find_by);

			$sheet->getStyle('A'.$rowCount.':Q'.$rowCount)->applyFromArray($borderStyle);
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
			
		}
	}
}	