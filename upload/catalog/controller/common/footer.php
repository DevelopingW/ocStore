<?php
class ControllerCommonFooter extends Controller {
    public function index() {
        $this->load->language('common/footer');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                );
            }
        }

        $data['contact'] = $this->url->link('information/contact');
        $data['return'] = $this->url->link('account/return/add', '', true);
        $data['sitemap'] = $this->url->link('information/sitemap');
        $data['tracking'] = $this->url->link('information/tracking');
        $data['manufacturer'] = $this->url->link('product/manufacturer');
        $data['voucher'] = $this->url->link('account/voucher', '', true);
        $data['affiliate'] = $this->url->link('affiliate/login', '', true);
        $data['special'] = $this->url->link('product/special');
        $data['account'] = $this->url->link('account/account', '', true);
        $data['order'] = $this->url->link('account/order', '', true);
        $data['wishlist'] = $this->url->link('account/wishlist', '', true);
        $data['newsletter'] = $this->url->link('account/newsletter', '', true);

        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), '2010-'.date('Y', time()));

        // Whos Online
        if ($this->config->get('config_customer_online')) {
            $this->load->model('tool/online');

            if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                $url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            } else {
                $url = '';
            }

            if (isset($this->request->server['HTTP_REFERER'])) {
                $referer = $this->request->server['HTTP_REFERER'];
            } else {
                $referer = '';
            }

            $this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
        }

        $data['scripts'] = $this->document->getScripts('footer');

        //
        $sape = $this->getSape();
        $data['sape1'] = $sape->return_links(1);
        $data['sape2'] = $sape->return_links(1);
        $data['sape3'] = $sape->return_links(1);

        return $this->load->view('common/footer', $data);
    }

    /**
     * @return SAPE_client
     */
    public function getSape()
    {
        // Подгружаем модуль SAPE
        define('_SAPE_USER', '82b9fbe9d2c317ec38c9b21b225048cd');
        require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
        $o['host'] = 'voiptech.com.ua';
        $o['force_show_code'] = true;
        $o['charset'] = 'UTF-8';
        $o['multi_site'] = true;

        return new SAPE_client($o);
    }
}
