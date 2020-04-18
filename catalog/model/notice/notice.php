<?php
class ModelNoticeNotice extends Model {

	public function getNotice($notice_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notice n LEFT JOIN " . DB_PREFIX . "notice_description nd ON (n.notice_id = nd.notice_id) WHERE n.notice_id = '" . (int)$notice_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getNotices() {
		$sql = "SELECT * FROM " . DB_PREFIX . "notice n LEFT JOIN " . DB_PREFIX . "notice_description nd ON (n.notice_id = nd.notice_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'nd.title'
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
