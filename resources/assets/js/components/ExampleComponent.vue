<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            Dashboard
            <span class="pull-right">Balance: {{ user.balance | balance }}</span>
        </div>

        <div class="panel-body">
            <form action="/bet" method="POST">

                <div class="row">

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="bet-amount"><small>BET AMOUNT</small></label>
                      <div class="input-group">
                        <input type="text" name="bet_amount" @keyup="fixBetAmount" v-model="bet_amount" id="bet-amount" class="form-control" placeholder="Bet amount">
                        <span class="input-group-btn">
                          <button @click="halveAmount" class="btn btn-default btn-primary" type="button">1/2</button>
                          <button @click="doubleAmount" class="btn btn-default btn-primary" type="button">2x</button>
                          <button @click="maxAmount" class="btn btn-default btn-primary" type="button">MAX</button>
                        </span>
                      </div><!-- /input-group -->
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="bet-amount"><small>PROFIT ON WIN</small></label>
                      <div style="font-size: 20px;">{{ profit }}</div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-sm-4 ">
                    <small><strong>ROLL UNDER</strong></small>
                    <div class="value-field">{{roll}}</div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="payout"><small>PAYOUT</small></label>
                      <div class="value-field" v-show="!payout_editing" @click="payout_editing = true">
                        {{ payout }}x
                        <span class="glyphicon glyphicon-edit"></span>
                      </div>
                      <div class="input-group" v-show="payout_editing">
                        <input @keydown.enter.prevent="savePayout" type="number" step="0.0001" id="payout" v-model="payout_edit" class="form-control"/>
                        <input type="hidden" name="payout" v-model="payout" />
                        <span class="input-group-btn">
                          <button @click="savePayout" class="btn btn-default btn-primary" type="button">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                          </button>
                          <button @click="savePayoutCancel" class="btn btn-default btn-primary" type="button">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                          </button>
                        </span>
                      </div><!-- /input-group -->
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="win-chance"><small>WIN CHANCE</small></label>
                      <div class="value-field" v-if="!win_chance_editing" @click="win_chance_editing = true">
                        {{ win_chance }}%
                        <span class="glyphicon glyphicon-edit"></span>
                      </div>
                      <div class="input-group" v-if="win_chance_editing">
                        <input @keydown.enter.prevent="saveWinChance" type="number" step="0.01" name="win_chance" id="win-chance" v-model="win_chance_edit" class="form-control"/>
                        <span class="input-group-btn">
                          <button @click="saveWinChance" class="btn btn-default btn-primary" type="button">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                          </button>
                          <button @click="saveWinChanceCancel" class="btn btn-default btn-primary" type="button">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                          </button>
                        </span>
                      </div><!-- /input-group -->
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 text-center">
                    <button @click="rolling = true" type="submit" class="btn btn-primary btn-lg btn-roll">
                      <span v-show="!rolling">ROLL DICE</span>
                      <img v-show="rolling" src="/img/rolling.svg" />
                    </button>
                    <input type="hidden" name="_token" :value="csrf_token">
                  </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        let bet = window.bet ? window.bet : {value: 1, payout: 2};
        return {
          user: window.user,
          csrf_token: document.getElementsByTagName('meta')['csrf-token'].getAttribute('content'),
          rolling: false,
          payout_editing: false,
          win_chance_editing: false,
          bet_amount: Number(bet.value).toFixed(4),
          payout_field: Number(bet.payout).toFixed(4),
          payout_edit: Number(bet.payout).toFixed(4),
          win_chance_edit: Number(99 / bet.payout).toFixed(2)
        };
      },
      mounted() {
          console.log('Component mounted.')
      },
      methods: {
        fixBetAmount: function() {
          let num = Number(this.bet_amount);
          let amount = this.bet_amount.toString();
          if (num < 0 || isNaN(num)) {
            amount = "0.0001";
          }
          if (amount.indexOf(".") != -1 && amount.length - amount.indexOf(".") > 5) {
            amount = amount.substr(0, amount.indexOf(".") + 5);
          }

          this.bet_amount = amount;
        },
        doubleAmount: function() {
          let num = Number(this.bet_amount);
          if (num <= 0 || isNaN(num)) {
            this.bet_amount = 0.0001;
          } else {
            this.bet_amount = (num * 2).toFixed(4);
          }
        },
        halveAmount: function() {
          let num = Number(this.bet_amount);
          if (num <= 0 || isNaN(num)) {
            this.bet_amount = 0.0000;
          } else {
            this.bet_amount = (num / 2).toFixed(4);
          }
        },
        maxAmount: function() {
          this.bet_amount = this.user.balance;
        },
        savePayout: function() {
          this.payout = this.payout_edit;
          this.payout_editing = false;
          return false;
        },
        savePayoutCancel: function() {
          this.payout_edit = this.payout;
          this.payout_editing = false;
        },
        saveWinChance: function() {
          this.win_chance = this.win_chance_edit;
          this.win_chance_editing = false;
          return false;
        },
        saveWinChanceCancel: function() {
          this.win_chance_edit = this.win_chance;
          this.win_chance_editing = false;
        }
      },
      computed: {
        profit: function() {
          return ((this.payout_field - 1) * this.bet_amount).toFixed(4);
        },
        roll: function() {
          return this.win_chance;
        },
        payout: {
          get: function () {
            this.payout_field = Number(this.payout_field);
            return this.payout_field;
          },
          set: function (payout) {
            payout = Number(payout);
            if (payout < 1.01) payout = 1.01;
            if (payout > 9900) payout = 9900.0;
            payout = payout.toFixed(4);
            this.payout_field = payout;
          }
        },
        win_chance: {
          get: function () {
            return (99 / this.payout_field).toFixed(2);
          },
          set: function (win_chance) {
            win_chance = Number(win_chance);
            if (win_chance < 0.01) win_chance = 0.01;
            if (win_chance > 98) win_chance = 98.0;
            win_chance = win_chance.toFixed(2);
            this.payout_field = (99 / win_chance).toFixed(4)
          }
        }
      }
    }
</script>
