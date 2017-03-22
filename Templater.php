<?php
	/*
	 * Templater class by ACWPD
	 * 
	 * @author Aaron coquet [git@acwpd.com]
	 * @copyright 2017 ACWPD / Aaron Coquet
	 */

	namespace ACWPD;
	/*
	 * Templater Class
	 * Builds an HTML Template based on a .html.tpl file
	 */

	class Templater {

		private $template_directory;
		private $template;
		private $includes;

		/* 
		 * @param string $template Name of a template file located in $_SERVER['DOCUMENT_ROOT']/private/templates
		 * @param array $includes Variables to pass to the template. Will be prefixed with 'templater_'
		 * 
		 * @return bool Answers 'This is a valid template file'
		 */
		public function __construct(string $template, array ...$includes) {
			/* Update this line to move the template directory */
			$this->template_directory = $_SERVER['DOCUMENT_ROOT'] . '/private/templates/';

			$this->template = $this->template_directory . $template . 'html.tpl';
			$this->includes = $includes;
			if(!file_exists($this->template)) {
				throw new Exception("No such template file", 1);
				return false;
			}
			return true;
		}

		/* 
		 * Build the requested page.
		 * 
		 * @returns string HTML for the page 
		 */
		public function getHTML() {
			extract($this->includes,EXTR_PREFIX_ALL,'templater_');
			ob_start();
			require_once($this->template);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
	