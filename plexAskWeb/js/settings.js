// settings js for settings page
var userRights = new Vue({
  el: '#d_user_rights',
  mounted: function() {
    this.getUsers()
  },
  data: {
    users: ''
  },
  methods: {
    getUsers: function() {

      // starte ajax Funktion von ajax.js
      var promise = getAjax("getUsers");
      promise.done(function(data) {
        userRights.users = data;
      });


    }, // end getUsers

    // to add a user to the Rights panel
    addUser: function() {
      //console.log(userRights.getObject());
      var accMail = $('#pa_input_accounts').val();
      this.users.push({"email":accMail,"adminPanel":false,"editSettings":false});
    },

    deleteUser: function(index) {

      Swal.fire({
        title: 'Bist du sicher?',
        text: "Die Änderungen können nicht Rückgängig gemacht werden!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Löschen!'
      }).then((result) => {
        if (result.value) {

          var promise = deleteUser(index);
          promise.done(function(data) {
            data = JSON.stringify(data);
            var response = $.parseJSON(data);
            if(response.deleted == true) {
              userRights.users.splice(index, 1);
            } else {
              console.log("Error");
            }
          });

        }
      })

    },

    changeRights: function(index, action) {
      switch(action) {
        case 1:
          if(userRights.users[index]["adminPanel"] == true) {
            userRights.$set(userRights.users[index], "adminPanel", false);
          } else {
            userRights.$set(userRights.users[index], "adminPanel", true);
          }
          break;
        case 2:
          if(userRights.users[index]["editSettings"] == true) {
            userRights.$set(userRights.users[index], "editSettings", false);
          } else {
            userRights.$set(userRights.users[index], "editSettings", true);
          }
          break;
      }

    },

    getObject: function() {
      return JSON.stringify(this.users);
    }

  }// end methods
})

function warningDialog() {

}
