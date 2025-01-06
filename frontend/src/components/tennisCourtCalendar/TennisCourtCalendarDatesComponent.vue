<template>

  <div class="d-flex align-items-center flex-column justify-content-center">

    <form @submit.prevent="formSubmit()">
      <div class="mb-4">
        <div class="">
          <h3 class="">Data</h3>
          <input type="date" v-model="register.dateStart" class="form-control me-2" id="date" required>
        </div>
      </div>
      <div class="mb-4">
        <h3 class="">Horários</h3>

        <div class="d-flex">
          <div class="me-3">
            <label class="form-label" for="timeStart">Início</label>
            <input type="time" v-model="register.timeStart" class="form-control" id="timeStart" required>
          </div>

          <div class="">
            <label class="form-label" for="timeStart">Fim</label>
            <input type="time" v-model="register.timeEnd" class="form-control" id="timeEnd" required>
          </div>
        </div>
      </div>
      <div class="text-center">
        <button class="btn btn-lg btn-success" type="submit"><i class="bi bi-calendar-check me-2"></i>Reservar</button>
      </div>
    </form>

  </div>
</template>

<script>
import Api from '@/services/Api';
import { mapGetters } from 'vuex';

export default {
  name: 'TennisCourtCalendarDatesComponent',
  props: {
  },
  data() {
    return {
      register: {}
    }
  },
  mounted() {
  },
  computed: {
    ...mapGetters('tennisCourtCalendar', ['tennisCourtId']),
  },
  methods: {
    formSubmit() {
      Api.create('tennis-court-calendar', { id: this.tennisCourtId, ...this.register }).then((response) => {
        console.log('TennisCourtCalendarDatesComponent', response);
      })
    }
  }
}
</script>