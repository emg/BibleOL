<?php

class Mod_urls extends CI_Model {
    public function __construct() {
        parent::__construct();

//        $this->config->load('ol');
        $this->load->database();
    }

    /// Gets Hebrew buttons
    public function get_heb_buttons() {
        return array(
            //     Label                              Sortorder range
            array("&#x05d0;&#x05d1;-&#x05d0;&#x05d9;", "ab","ak"),
            array("&#x05d0;&#x05db;-&#x05d0;&#x05e8;", "ak","au"),
            array("&#x05d0;&#x05e9;-&#x05d1;&#x05e1;", "au","bp"),
            array("&#x05d1;&#x05e2;-&#x05d2;&#x05d6;", "bp","ch"),
            array("&#x05d2;&#x05d7;-&#x05d3;&#x05e7;", "ch","dt"),
            array("&#x05d3;&#x05e8;-&#x05d6;&#x05e7;", "dt","gt"),
            array("&#x05d6;&#x05e8;-&#x05d7;&#x05dc;", "gt","hm"),
            array("&#x05d7;&#x05de;-&#x05d7;&#x05e9;", "hm","hv"),
            array("&#x05d7;&#x05ea;-&#x05d9;&#x05db;", "hv","jl"),
            array("&#x05d9;&#x05dc;-&#x05db;&#x05d1;", "jl","kd"),
            array("&#x05db;&#x05d3;-&#x05dc;&#x05d5;", "kd","lg"),
            array("&#x05dc;&#x05d6;-&#x05de;&#x05d6;", "lg","mh"),
            array("&#x05de;&#x05d7;-&#x05de;&#x05e1;", "mh","mp"),
            array("&#x05de;&#x05e2;-&#x05de;&#x05e9;", "mp","mv"),
            array("&#x05de;&#x05ea;-&#x05e0;&#x05e2;", "mv","nq"),
            array("&#x05e0;&#x05e4;-&#x05e1;&#x05e2;", "nq","oq"),
            array("&#x05e1;&#x05e4;-&#x05e2;&#x05db;", "oq","pl"),
            array("&#x05e2;&#x05dc;-&#x05e2;&#x05e9;", "pl","pv"),
            array("&#x05e2;&#x05ea;-&#x05e4;&#x05e8;", "pv","qu"),
            array("&#x05e4;&#x05e9;-&#x05e7;&#x05d0;", "qu","sb"),
            array("&#x05e7;&#x05d1;-&#x05e7;&#x05e9;", "sb","ta"),
            array("&#x05e8;&#x05d0;-&#x05e8;&#x05e4;", "ta","tr"),
            array("&#x05e8;&#x05e6;-&#x05e9;&#x05d7;", "tr","ui"),
            array("&#x05e9;&#x05d8;-&#x05e9;&#x05e2;", "ui","uq"),
            array("&#x05e9;&#x05e4;-&#x05ea;&#x05de;", "uq","vn"),
            array("&#x05ea;&#x05e0;-&#x05ea;&#x05e9;", "vn","zz"),
            );
    }

    /// Gets Aramaic buttons
    public function get_aram_buttons() {
        return array(
            //     Label                              Sortorder range
            array("&#x05d0;&#x05d1;-&#x05de;&#x05d5;", "ab","mg"),
            array("&#x05de;&#x05d6;-&#x05ea;&#x05ea;", "mg","zz"),
            );
    }

    public function get_glosses(string $language, string $from, string $to) {
        $dbh = new SQLite3('db/glossdb_hebrew.db', SQLITE3_OPEN_READONLY);
        $rs = $dbh->query("SELECT * FROM heb_en WHERE language='$language' AND sortorder>='$from' AND sortorder<'$to' ORDER BY sortorder");
        $last_lex = '';
        while ($record = $rs->fetchArray(SQLITE3_ASSOC)) {
            // Only take each lexeme once, ignoring vs
            if ($record['lex']!==$last_lex) {
                $result[] = $record;
                $last_lex = $record['lex'];
            }
        }
        return $result;
    }

    public function get_common_glosses(string $language) {
        $dbh = new SQLite3('db/glossdb_hebrew.db', SQLITE3_OPEN_READONLY);
        $rs = $dbh->query("SELECT * FROM heb_en WHERE language='$language' ORDER BY tally DESC LIMIT " . (2*FREQUENT_GLOSSES));
        $last_lex = '';
        $result = array();
        while ($record = $rs->fetchArray(SQLITE3_ASSOC)) {
            // Only take each lexeme once, ignoring vs
            if ($record['lex']!==$last_lex) {
                if (count($result)>=FREQUENT_GLOSSES) {
                    // Break when we have a record with a smaller tally
                    if ($tally > $record['tally'])
                        break;
                }
                $result[] = $record;
                $last_lex = $record['lex'];
                if (count($result)==FREQUENT_GLOSSES)
                    $tally = $record['tally'];
            }
        }
        assert(count($result)>=FREQUENT_GLOSSES); // To ensure that the SQL LIMIT is high enough

        return $result;
    }

    public function get_heb_urls(string $language, array &$words) {
        foreach ($words as &$w) {
            $query = $this->db->where('lex',$w['lex'])->where('language',$language)->get('heb_urls');
            if ($query->num_rows()>0)
                $w['urls'] = $query->result();
        }
    }

    public function set_heb_url(integer $id, string $link, string $icon) {
        $this->db->where('id',$id)->update('heb_urls',array('url' => $link,
                                                            'icon' => $icon));
    }

    public function create_heb_url(string $lex, string $language, string $link, string $icon) {
        $this->db->insert('heb_urls',array('lex' => $lex,
                                           'language' => $language,
                                           'url' => $link,
                                           'icon' => $icon));
    }

    public function delete_heb_url(integer $id) {
        $this->db->where('id',$id)->delete('heb_urls');
    }
  }