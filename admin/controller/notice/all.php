<?php
class ControllerNoticeAll extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('notice/all');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notice/all');

		$this->getList();
    }

    public function add() {
		$this->load->language('notice/all');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notice/all');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_notice_all->addNotice($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('notice/all');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notice/all');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_notice_all->editNotice($this->request->get['notice_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}
    
    protected function getList() {
		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'nd.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		$data['add'] = $this->url->link('notice/all/add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('notice/all/delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['notices'] = array();

		$filter_data = array(
			'filter_title'     => $filter_title,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_pagination'),
			'limit'           => $this->config->get('config_pagination')
		);

		$notice_total = $this->model_notice_all->getTotalNotices();

		$results = $this->model_notice_all->getNotices($filter_data);

		foreach ($results as $result) {
			$data['notices'][] = array(
				'notice_id'    => $result['notice_id'],
				'title'            => $result['title'],
				'sort'      => $result['sort'],
				'edit'            => $this->url->link('notice/all/edit', 'user_token=' . $this->session->data['user_token'] . '&notice_id=' . $result['notice_id'] . $url)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_title'] = $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . '&sort=nd.title' . $url);
		$data['sort_sort'] = $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . '&sort=n.sort' . $url);

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $notice_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		));

		$data['results'] = sprintf($this->language->get('text_pagination'), ($notice_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($notice_total - $this->config->get('config_pagination'))) ? $notice_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $notice_total, ceil($notice_total / $this->config->get('config_pagination')));

		$data['filter_title'] = $filter_title;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('notice/notice_list', $data));
    }
    
    protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'notice/all')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['notice_description'] as $language_id => $value) {
			if ((utf8_strlen(trim($value['title'])) < 3) || (utf8_strlen($value['title']) > 200)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
            }
            
            if (utf8_strlen(trim($value['summary'])) < 3) {
				$this->error['summary'][$language_id] = $this->language->get('error_summary');
            }
            
            if (utf8_strlen(trim($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'notice/all')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    protected function getForm() {
        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');
        
		$data['text_form'] = !isset($this->request->get['notice_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
        }
        
        if (isset($this->error['summary'])) {
			$data['error_summary'] = $this->error['summary'];
		} else {
			$data['error_summary'] = array();
        }
        
        if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		if (!isset($this->request->get['notice_id'])) {
			$data['action'] = $this->url->link('notice/all/add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('notice/all/edit', 'user_token=' . $this->session->data['user_token'] . '&notice_id=' . $this->request->get['notice_id'] . $url);
		}

		$data['cancel'] = $this->url->link('notice/all', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['notice_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$notice_info = $this->model_notice_all->getNotice($this->request->get['notice_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['notice_description'])) {
			$data['notice_description'] = $this->request->post['notice_description'];
		} elseif (isset($this->request->get['notice_id'])) {
			$data['notice_description'] = $this->model_notice_all->getDescriptions($this->request->get['notice_id']);
		} else {
			$data['notice_description'] = array();
        }
        
        if (isset($this->request->post['sort'])) {
			$data['sort'] = $this->request->post['sort'];
		} elseif (!empty($notice_info)) {
			$data['sort'] = $notice_info['sort'];
		} else {
			$data['sort'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($notice_info)) {
			$data['status'] = $notice_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('notice/notice_form', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 5;
		}

		if ($filter_title) {
			$this->load->model('notice/all');

			$filter_data = array(
				'filter_title'  => $filter_title,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_notice_all->getNotices($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'notice_id' => $result['notice_id'],
					'title'       => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
