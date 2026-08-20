<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            Chat
        </div>

        <div class="panel-body">
            <div class="col-md-12">
                <ul>
                    <li v-for="data in messages">
                        [{{ data.created_at | time }}] <strong>{{ data.username }}: </strong>
                        {{ data.message }}
                    </li>
                </ul>

                <form @submit.prevent="sendMessage" action="/" method="post">
                    <input class="form-control" type="text" v-model="newMessage" placeholder="Enter your message here">
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          messages: [],
          newMessage: ''
        };
      },
      mounted() {
        let self = this;

        $.getJSON("/chat", function(data) {
          self.messages = data.data;
        });

        Echo.channel('chat')
          .listen('MessageReceived', (e) => {
            self.messages.unshift(e.message);
          });
      },
      methods: {
        sendMessage: function() {
          $.getJSON("/chat/store?message=" + this.newMessage);
          this.newMessage = '';
        }
      }
    }
</script>
