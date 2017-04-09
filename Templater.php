<?php
	/*
	 * Templater class by ACWPD
	 * 
	 * @author Aaron coquet [git@acwpd.com]
	 * @copyright 2017 ACWPD / Aaron Coquet
	 */

	namespace ACWPD\Helpers;
	/*
	 * Templater Class
	 * Builds an HTML Template based on a .html.php file
	 */

	class Templater {

		private $template_directory;
		private $template;
		private $includes;
		private $title;
		private $body;
		private $css;
		private $js;
		const REPLACEABLE = [
			'body',
			'title',
			'css',
			'js'
		];

		/* 
		 * @param string $template Name of a template file located in DOCUMENT_ROOT/protected/templates
		 * @param array $includes Variables to pass to the template. Will be prefixed with 'templater_'
		 * 
		 * @return bool Answers 'This is a valid template file'
		 */
		public function __construct(string $template, array ...$includes) {
			/* Update this line to move the template directory */
			$this->template_directory = DOCUMENT_ROOT . '/protected/templates/';

			$this->template = $this->template_directory . $template . '.html.php';
			$this->includes = $includes;
			if(!file_exists($this->template)) {
				throw new \Exception("No such template file", 0);
				return false;
			}
			return true;
		}

		public function setBody(string $template, array ...$include) {
			if(file_exists($this->template_directory . $template . '.html.php')) {
				ob_start();
				require($this->template_directory . $template . '.html.php');
				$this->body = ob_get_contents();
				ob_end_clean();
				
				return true;
			}
			throw new \Exception("No such template file", 0);
			return false;
		}

		public function appendToBody(string $template) {
			if(file_exists($this->template_directory . $template . '.html.php')) {
				ob_start();
				require($this->template_directory . $template . '.html.php');
				$out = ob_get_contents();
				ob_end_clean();
				$this->body .= $out;
				return true;
			}
			throw new \Exception("No such template file", 0);
			return false;
		}

		public function prependToBody(string $template) {
			if(file_exists($this->template_directory . $template . '.html.php')) {
				ob_start();
				require($this->template_directory . $template . '.html.php');
				$this->body = $template . ob_get_contents();
				ob_end_clean();
				return true;
			}
			throw new \Exception("No such template file", 0);
			return false;
		}

		/* 
		 * Build the requested page.
		 * 
		 * @returns string HTML for the page 
		 */
		public function getHTML() {
			$title = $this->title ?? '';
			$body = $this->body ?? '';
			$css = $this->css ?? '';
			$js = $this->js ?? '';
			extract($this->includes,EXTR_PREFIX_ALL,'templater');
			ob_start();
			require_once($this->template);
			$html = ob_get_contents();
			ob_end_clean();
			foreach ($this::REPLACEABLE as $key) {
				$html = str_replace("{{".$key."}}",$$key,$html);
			}
			return $html;
		}
	}
	