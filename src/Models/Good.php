<?php

namespace App\Models;

class Good
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getGoodsWithFields()
    {
        $sql = "
            SELECT 
                g.id,
                g.name as good_name,
                MAX(CASE WHEN af.sort_no = 1 THEN af.name END) as field1_name,
                MAX(CASE WHEN af.sort_no = 1 THEN afv.name END) as value1,
                MAX(CASE WHEN af.sort_no = 2 THEN af.name END) as field2_name,
                MAX(CASE WHEN af.sort_no = 2 THEN afv.name END) as value2
            FROM goods g
            LEFT JOIN additional_goods_field_values agfv ON g.id = agfv.good_id AND agfv.is_deleted = 0
            LEFT JOIN additional_fields af ON agfv.additional_field_id = af.id AND af.is_deleted = 0
            LEFT JOIN additional_field_values afv ON agfv.additional_field_value_id = afv.id AND afv.is_deleted = 0
            WHERE g.is_deleted = 0
            GROUP BY g.id, g.name
            ORDER BY g.id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
