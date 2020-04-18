<?php
class ControllerNoticeNotice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('notice/notice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('notice/notice');
		$this->getList();
	}

	public function view(){

		$notice_id = 0;

		$this->load->model('notice/notice');

		if (isset($this->request->get['notice_id'])) {
			$notice_id = $this->request->get['notice_id'];
		}

		$notice_info = $this->model_notice_notice->getNotice($notice_id);

		$data['notice'] = [
			'title' => $notice_info['title'],
			'description' => htmlspecialchars_decode($notice_info['description'])
		];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('notice/notice_read', $data));
	}

	protected function getList() {

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['notices'] = array();

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_pagination'),
			'limit'           => $this->config->get('config_pagination')
		);

		$notice_total = $this->model_notice_notice->getTotalNotices();

		$results = $this->model_notice_notice->getNotices();

		foreach ($results as $result) {
			$data['notices'][] = array(
				'notice_id'    => $result['notice_id'],
				'title'            => $result['title'],
				'summary'            => $result['summary'],
				'view'               => $this->url->link('notice/notice/view', 'language=' . $this->config->get('config_language') . '&notice_id=' . $result['notice_id']),
			);
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $notice_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('notice/notice', $url . '&page={page}')
		));

		$data['results'] = sprintf($this->language->get('text_pagination'), ($notice_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($notice_total - $this->config->get('config_pagination'))) ? $notice_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $notice_total, ceil($notice_total / $this->config->get('config_pagination')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('notice/notice_list', $data));
    }
}
