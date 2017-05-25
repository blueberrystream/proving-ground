## モデル設計
- マスターデータ
  - Item
    - ゲーム内ではおそらく「パーツ」と呼ばれる
    - 「部位」が設定されている
    - 「GCP」が設定されている
    - ステータス値を持っている
      - HP
      - ATK
      - DEF
  - Part
    - 「部位」を表す
  - Proprium
    - 「GCP」を表す
    - `property` の古語
  - Player
    - 「プレイヤー」を表す
- トランザクションデータ
  - PlayerItem
    - プレイヤーが所持している「パーツ」
  - PlayerEquipment
    - プレイヤーがしている「装備」
  - PlayerDeck
    - プレイヤーが編成した「デッキ」
  - PlayerBattleLog
    - バトルのログ
    - 1度のバトルで2レコード積まれる