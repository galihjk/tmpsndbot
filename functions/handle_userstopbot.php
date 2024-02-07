<?php
function handle_userstopbot($botdata){
    if(!empty($botdata["chat"]["id"])
    and !empty($botdata["from"]["id"])
    and $botdata["from"]["id"] == $botdata["chat"]["id"]
    and !empty($botdata["new_chat_member"]["status"])
    and $botdata["new_chat_member"]["status"] == "kicked"
    ){
        f("db_q")("update users set bot_active=null where id='".$botdata["from"]["id"]."'");
        return true;
    }
    return false;
}
/*
{
  "update_id": 732119727,
  "my_chat_member": {
    "chat": {
      "id": 2063236800,
      "first_name": "Jaya2nti",
      "username": "Jaya2nti",
      "type": "private"
    },
    "from": {
      "id": 2063236800,
      "is_bot": false,
      "first_name": "Jaya2nti",
      "username": "Jaya2nti",
      "language_code": "id"
    },
    "date": 1672908506,
    "old_chat_member": {
      "user": {
        "id": 5687994870,
        "is_bot": true,
        "first_name": "fwxy",
        "username": "fwxyzbot"
      },
      "status": "member"
    },
    "new_chat_member": {
      "user": {
        "id": 5687994870,
        "is_bot": true,
        "first_name": "fwxy",
        "username": "fwxyzbot"
      },
      "status": "kicked",
      "until_date": 0
    }
  }
}
*/