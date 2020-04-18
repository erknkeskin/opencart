<?php
class ModelNoticeAll extends Model {

    public function getNotice($notice_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notice n LEFT JOIN " . DB_PREFIX . "notice_description nd ON (n.notice_id = nd.notice_id) WHERE n.notice_id = '" . (int)$notice_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function editNotice($notice_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "notice SET `status` = '" . (int)$data['status'] . "', `sort` = '" . (int)$data['sort'] . "', date_modified=NOW() WHERE notice_id = '" . (int)$notice_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "notice_description WHERE notice_id = '" . (int)$notice_id . "'");

		foreach ($data['notice_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "notice_description SET notice_id = '" . (int)$notice_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', summary = '" . $this->db->escape($value['summary']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "'");
		}
	}

	public function addNotice($data)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "notice SET `status` = '" . (int)$data['status'] . "', `sort` = '" . (int)$data['sort'] . "', date_added=NOW(), date_modified=NOW()");

		$notice_id = $this->db->getLastId();

		foreach ($data['notice_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "notice_description SET notice_id = '" . (int)$notice_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', summary = '" . $this->db->escape($value['summary']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "'");
		}

		return $notice_id;
	}

	public function getNotices($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "notice n LEFT JOIN " . DB_PREFIX . "notice_description nd ON (n.notice_id = nd.notice_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_title'])) {
			$sql .= " AND nd.title LIKE '" . $this->db->escape((string)$data['filter_title']) . "%'";
		}

		$sort_data = array(
			'nd.title',
			'n.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY nd.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getDescriptions($notice_id) {
		$notice_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notice_description WHERE notice_id = '" . (int)$notice_id . "'");

		foreach ($query->rows as $result) {
			$notice_data[$result['language_id']] = array(
                'title' => $result['title'],
                'summary' => $result['summary'],
				'description' => $result['description'],
				'meta_title' => $result['meta_title'],
				'meta_keyword' => $result['meta_keyword'],
				'meta_description' => $result['meta_description']
            );
		}

		return $notice_data;
	}

	public function getTotalNotices() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notice");

		return $query->row['total'];
	}

}
