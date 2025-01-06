<template>

    <div class="d-flex align-items-center flex-column justify-content-center">

        <div class="d-flex justify-content-between w-100 my-2">
            <div>
                <button class="btn btn-outline-primary" id="previous" @click.prevent="previous()"
                    title="Mês Anterior"><i class="bi bi-arrow-bar-left"></i></button>
            </div>

            <div>
                <h3 class="card-header" id="monthAndYear"></h3>
            </div>

            <div><button class="btn btn-outline-primary" id="next" @click.prevent="next()" title="Próximo Mês"><i
                        class="bi bi-arrow-bar-right"></i></button>
            </div>
        </div>

        <table class="table table-bordered table-responsive-sm" id="calendar">
            <thead>
                <tr>
                    <th class="text-center">Sun</th>
                    <th class="text-center">Mon</th>
                    <th class="text-center">Tue</th>
                    <th class="text-center">Wed</th>
                    <th class="text-center">Thu</th>
                    <th class="text-center">Fri</th>
                    <th class="text-center">Sat</th>
                </tr>
            </thead>

            <tbody id="calendar-body">

            </tbody>
        </table>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="tciclModal" tabindex="-1" aria-labelledby="tciclModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tciclModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">

                    <p class="modal-error-top d-none text-danger text-center">Nenhum horário foi selecionado</p>

                    <table class="table table-bordered table-responsive-sm">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Hora Início</th>
                                <th class="text-center">Hora Fim</th>
                                <th class="text-center">Preço</th>
                            </tr>
                        </thead>

                        <form id="tciclModalForm">
                            <tbody id="tciclModalTbody">
                            </tbody>
                        </form>
                    </table>
                </div>

                <p class="modal-error-bottom d-none text-danger text-center">Nenhum horário foi selecionado</p>
                <div class="modal-footer align-items-center">
                    <button type="button" class="btn btn-success" @click.prevent="formSubmit()">Fazer o
                        Pagamento</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

</template>

<script>
//import Api from '@/services/Api';
import store from '@/store';
import { mapGetters } from 'vuex';
import { Modal, Tooltip } from "bootstrap";
import moment from 'moment';
import Api from '@/services/Api';
import LaravelEcho from '@/services/LaravelEcho';

export default {
    name: 'TennisCourtItemCalendar',
    props: {
    },
    data() {
        return {
            register: {},

            today: null,
            //month =["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
            months: [0, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],

            monthAndYear: null,

            currentMonth: null,
            currentYear: null,
            selectYear: null,
            selectMonth: null,

            actualDay: null,
            actualMonth: null,
            actualYear: null,

            selectedDay: null,
            selectedMonth: null,
            selectedYear: null,

            bgColerHourUnavailable: '#ff3236',

            tennisCourtId: this.$route.params.id,
        }
    },
    mounted() {
        this.today = moment().format('YYYY-MM-DD');//new Date(); //

        this.currentDay = Number(moment().format('DD')); //this.today.getMonth();
        this.currentMonth = moment().format('MM'); //this.today.getMonth();
        this.currentYear = Number(moment().year());//this.today.getFullYear();

        this.monthAndYear = document.getElementById("monthAndYear");
        //this.selectYear = document.getElementById("year");
        //this.selectMonth = document.getElementById("month");

        //this.selectedMonth = document.getElementById("month");
        //this.selectedYear = document.getElementById("year");

        //this.getActualDate();
        //this.getDaysInMonth();

        this.init();

        // var inputs = document.querySelectorAll('input[type="checkbox"]:checked')
        // inputs.forEach(function(e){
        //     e.addEventListener('click', function() {
        //         console.log('pegou os check');
        //     })
        // })

        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
        });
    },
    computed: {
        ...mapGetters('tennisCourtCalendar', ['tennisCourtCalendarForTennisCourtId', 'numberOfHoursPerDay']),
        ...mapGetters('tennisCourtOpeningHour', ['getTennisCourtOpeningHourItemsToTennisCourtId']),
    },
    methods: {

        ///
        async updateData() {
            await store.dispatch('tennisCourtCalendar/getItemsForTennisCourtId', this.$route.params.id);
            await store.dispatch('tennisCourtCalendar/numberOfHoursPerDay', this.$route.params.id);
        },

        ///
        async init() {
            await this.updateData();
            // this.showCalendar(this.currentMonth, this.currentYear).then(() => {
            //     this.onClikday();
            // });
            this.showCalendar(this.currentMonth, this.currentYear);
            this.onClikday();
        },

        ///
        onClikday() {
            //console.log('getTennisCourtOpeningHourItemsToTennisCourtId', this.getTennisCourtOpeningHourItemsToTennisCourtId);
            // to active Modal onClick
            var elDay = document.querySelectorAll('.active-modal');
            //tiles.forEach(tile => tile.addEventListener('click', this.handleTileClick))
            if (elDay?.length) {
                for (var i = 0; i < elDay.length; i++) {

                    elDay[i].addEventListener('click', (e) => {
                        this.openModal(e)
                    });
                }
            }
        },

        ///
        openModal(el) {
            console.log(el.target.textContent);
            this.showModal(el);
        },

        ///
        async showModal(el) {

            await this.updateData();

            var d = moment(el.target.id); //new Date(el.target.id);
            var dTarget = [Number(d.year()), d.format('MM'), d.format('DD')];

            // console.log('el.target.id', el.target.id);
            // console.log('moment el.target.id = dTarget', dTarget);
            // console.log("d", d.format('YYYY-MM-DD'));
            // console.log("moment().format('DD')", moment().format('YYYY-MM-DD'));
            // console.log("moment().isAfter()", moment().isAfter(d, 'day'));
            //moment(el.target.id).format('YYYY-MM-DD')

            //let nameMonth = d.toLocaleString('en', { month: 'long' });
            //let nameDay = (d.toLocaleDateString('en', { weekday: 'long' })).toLocaleLowerCase();
            let nameDay = (d.format('dddd')).toLocaleLowerCase();
            let myRenderTbody = '';

            //console.log('tennisCourtCalendarForTennisCourtId', this.tennisCourtCalendarForTennisCourtId);

            let tennisCourtCalendarForTennisCourtId = [];
            let tennisCourtCalendarForTennisCourtIdKey = [];

            //reverb = 'this.$route.params.id'.'year'.'month'
            //console.log('this.tennisCourtCalendarForTennisCourtId.data lll', this.tennisCourtCalendarForTennisCourtId.data[0]);

            for (const [, value] of Object.entries(this.tennisCourtCalendarForTennisCourtId.data)) {

                let tennisCourtCalendarForTennisCourtIdTimeStart = moment(value.time_start);
                let tennisCourtCalendarForTennisCourtIdTimeStartDate = tennisCourtCalendarForTennisCourtIdTimeStart.format('DD');
                let tennisCourtCalendarForTennisCourtIdTimeStartMonth = tennisCourtCalendarForTennisCourtIdTimeStart.format('MM');
                let tennisCourtCalendarForTennisCourtIdTimeStartYear = tennisCourtCalendarForTennisCourtIdTimeStart.year();
                let tennisCourtCalendarForTennisCourtIdTimeStartHours = tennisCourtCalendarForTennisCourtIdTimeStart.format('HH');
                let tennisCourtCalendarForTennisCourtIdTimeStartMinutes = tennisCourtCalendarForTennisCourtIdTimeStart.format('mm');

                tennisCourtCalendarForTennisCourtIdKey = [
                    tennisCourtCalendarForTennisCourtIdTimeStartYear,
                    tennisCourtCalendarForTennisCourtIdTimeStartMonth,
                    tennisCourtCalendarForTennisCourtIdTimeStartDate,
                    tennisCourtCalendarForTennisCourtIdTimeStartHours,
                    tennisCourtCalendarForTennisCourtIdTimeStartMinutes,
                ].join('');

                tennisCourtCalendarForTennisCourtId[Number(tennisCourtCalendarForTennisCourtIdKey)] = value.status === 'paid';
            }

            //console.log('datas <><><>', d)
            //console.log('datas ///////', tennisCourtCalendarForTennisCourtId)
            //console.log('this.getTennisCourtOpeningHourItemsToTennisCourtId', this.getTennisCourtOpeningHourItemsToTennisCourtId);

            for (const [key, value] of Object.entries(this.getTennisCourtOpeningHourItemsToTennisCourtId.data)) {

                //console.log('value.day == nameDay',value.day == nameDay, value.day, nameDay);

                if (value.day == nameDay) {

                    let difHour = value.hour_end - value.hour_start;
                    var i = 0;
                    while (i < difHour) {
                        let hourStart = (Number(value.hour_start) + Number(i));
                        hourStart = (hourStart < 10 ? '0' + hourStart : hourStart);
                        let hourEnd = (Number(hourStart) + 1);
                        hourEnd = (hourEnd < 10 ? '0' + hourEnd : hourEnd);

                        let trTagId = Number(dTarget.join('') + hourStart + '00');

                        //console.log("dTarget + hourStart + 00", (dTarget.join('') + hourStart + '00'))
                        //console.log("tennisCourtCalendarForTennisCourtId[dTarget + hourStart + 00]", tennisCourtCalendarForTennisCourtId[Number(dTarget.join('') + hourStart + '00')]);

                        let cellStyle = '';
                        let cellClassDisabled = '';
                        //flexSwitchCheckChecked' + i.checked = !el.checked;
                        let trOnclick = 'onClick="function hi(){var el = document.querySelector(\'#checkDay' + i + '\');el.checked = !el.checked;};hi()"';
                        let myRenderTbodyInputForm = '';
                        let roleButton = 'role="button"';
                        myRenderTbodyInputForm = '<div class="form-check form-switch" >';
                        //myRenderTbodyInputForm += '<input class="form-check-input" type="checkbox" id="checkDay' + i + '" ' + cellClassDisabled + ' value="' + dTarget.join('-') + ' ' + hourStart + ':00">';
                        myRenderTbodyInputForm += '<input class="form-check-input" type="checkbox" id="checkDay' + i + '" ' + cellClassDisabled + ' value="' + key + '" data-date="' + dTarget.join('-') + ' ' + hourStart + ':00">';
                        //myRenderTbodyInputForm += '<label class="form-check-label" for="checkDay' + i + '"></label>';
                        myRenderTbodyInputForm += '</div>';

                        if (tennisCourtCalendarForTennisCourtId[trTagId]) {
                            cellStyle = 'style="background-color: ' + this.bgColerHourUnavailable + '; cursor: not-allowed; "';
                            cellClassDisabled = 'disabled checked';
                            trOnclick = 'onClick="alert(\'Já Ocupado\')"';
                            //console.log('cellStyle', cellStyle);

                            roleButton = '';
                            myRenderTbodyInputForm = '';
                        }

                        myRenderTbody += '<tr id="' +this.tennisCourtId+ trTagId + '" ' + roleButton + '>';
                        myRenderTbody += '<td id="modal-' + el.target.id + '" ' + cellStyle + ' class="tag-to-render-input-check">';
                        myRenderTbody += myRenderTbodyInputForm;
                        myRenderTbody += '</td>';
                        myRenderTbody += '<td ' + trOnclick + ' ' + cellStyle + '>' + hourStart + ':00</td>';
                        myRenderTbody += '<td ' + trOnclick + ' ' + cellStyle + '>' + hourEnd + ':00</td>';
                        myRenderTbody += '<td ' + trOnclick + ' ' + cellStyle + '>R$ ' + value.price + '</td>';
                        myRenderTbody += '</tr>';
                        i++;
                    }
                }

            }

            var myModalEl = document.getElementById("tciclModal")

            this.uniqueModal = new Modal(myModalEl, { keyboard: false });
            this.uniqueModal.show();

            var myModalElLabel = document.getElementById("tciclModalLabel")
            var myModalElTbody = document.getElementById("tciclModalTbody")

            myModalElLabel.innerHTML = `Disponíveis para ${(dTarget.reverse()).join('-')}`;

            myModalElTbody.innerHTML = myRenderTbody;

            await this.broadcastTennisCourtCalendarForDateAndTennisCourtId();
        },

        ///
        closeModal() {
            this.uniqueModal.hide();
        },

        ///
        showCalendar(month, year) {

            //month = Number(month);

            let firstDay = 1;//(new Date(year, Number(month))).getDay();
            let tbl = document.getElementById("calendar-body"); // body of the calendar

            // clearing all previous cells
            if (tbl) {
                tbl.innerHTML = "";
            }

            // filing data about month and in the page via DOM.
            this.monthAndYear.innerHTML = this.months[Number(month)] + " " + year;
            //this.selectYear.value = year;
            //this.selectMonth.value = month;

            // creating all cells
            let date = 1;
            for (let i = 0; i < 6; i++) {
                // creates a table row
                let row = document.createElement("tr");

                //creating individual cells, filing them up with data.
                for (let j = 0; j < 7; j++) {

                    if (!this.daysInMonth(date, month, year)) {
                        break;
                    }

                    if (i === 0 && j < firstDay) {
                        let cell = document.createElement("td");
                        //cell.setAttribute("style", "background: #ccc");
                        cell.classList.add("tcc-cell-empty");
                        cell.setAttribute("role", 'button');

                        let cellText = document.createTextNode("");
                        cell.appendChild(cellText);
                        row.appendChild(cell);
                    }
                    else {
                        let cell = document.createElement("td");
                        cell.classList.add("text-center");

                        //console.log('this.tennisCourtCalendarForTennisCourtId.data',this.tennisCourtCalendarForTennisCourtId.data);

                        let cellText = document.createTextNode(date);

                        //se a data for maior ou igual ao dia atual
                        // necessário para deshabilitar data anterior ao dia atual
                        //console.log("moment().isSameOrBefore(year+'-'+month+'-'+date)", moment().format('YYYY-MM-DD'),year+'-'+month+'-'+date,  moment().isSameOrBefore(year+'-'+month+'-'+date, 'day'));
                        if (moment().isSameOrBefore(year + '-' + month + '-' + date, 'day')) {
                            cell.setAttribute("role", 'button');
                            cell.classList.add("tcc-cell");
                            cell.setAttribute("id", `${year}-${month}-${(date < 10 ? '0' : '') + date}`);
                            // to active Modal onClick
                            cell.classList.add("active-modal");
                            //cell.addEventListener('click', this.openModal(), false);
                        } else {
                            cell.classList.add("tcc-cell-disabled");
                        }

                        // color today's date
                        if (moment().isSame(year + '-' + month + '-' + date, 'day')) {
                            cell.classList.remove("tcc-cell");
                            cell.classList.add("border-5");
                            cell.classList.add("border-primary");
                            //cell.classList.add("bg-secondary");
                        }

                        // deixe essa linha por último por conta da set classList
                        this.findInObject(`${year}-${month}-${(date < 10 ? '0' : '') + date}`, this.tennisCourtCalendarForTennisCourtId.data, cell);

                        cell.appendChild(cellText);
                        row.appendChild(cell);

                        date++;
                    }
                }
                if (tbl) {
                    tbl.appendChild(row); // appending each row into calendar body.
                }
            }
        },

        ///
        daysInMonth(iDay, iMonth, iYear) {
            //return 32 - new Date(iYear, iMonth, 32).getDate();
            //return iDay < new Date(iYear, iMonth + 1, 0).getDate()
            return moment(iYear + '-' + iMonth + '-' + iDay).isValid();
        },

        ///
        findInObject(propertyValue, objectToSearch, cell) {

            //console.log('propertyValue, objectToSearch, cell',propertyValue, objectToSearch, cell);

            let i = 0;
            let thisStyle = '';
            let numberOfHoursPerDay = this.numberOfHoursPerDay.data;
            let r = false;
            for (const [, value] of Object.entries(objectToSearch)) {

                let timeStart = this.paramsForThisDate(value);

                if (String(timeStart.numbers.join('-')) == String(propertyValue)) {

                    if (i == 0) {
                        //numberOfHoursPerDay = Math.round(24 / numberOfHoursPerDay[(timeStart[1][1]).toLowerCase()]);
                        numberOfHoursPerDay = 100 / numberOfHoursPerDay[(timeStart.names.nameDay).toLowerCase()];
                        i = numberOfHoursPerDay;
                        //thisStyle = `${this.bgColerHourUnavailable} 0%, ${this.bgColerHourUnavailable} ${i}%, #ffffff ${i}%`;
                        thisStyle = ' ' + this.bgColerHourUnavailable + ' 0%, ' + this.bgColerHourUnavailable + '  ' + i + '%,#ffffff ' + i + '%, ';
                    }
                    cell.classList.add("text-center");
                    cell.setAttribute("style", "background: linear-gradient(to right, " + thisStyle + " transparent 100%) !important");

                    thisStyle += '  ' + this.bgColerHourUnavailable + '  ' + i + '%, ' + this.bgColerHourUnavailable + '  ' + (i + numberOfHoursPerDay) + '%, #ffffff ' + (i + numberOfHoursPerDay) + '%, ';
                    i = i + numberOfHoursPerDay;
                    r = true;
                }

            }
            return r;
        },

        ///
        paramsForThisDate(date) {
            var d = new Date(date.time_start),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear(),

                nameMonth = d.toLocaleString('en', { month: 'long' }),
                nameDay = d.toLocaleDateString('en', { weekday: 'long' });

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            //return [year, month, day].join('-');
            return {
                numbers: [year, month, day],
                names: { nameMonth: nameMonth, nameDay: nameDay },
                prices: { price: date.price, price_promo: date.price_promo }
            };
        },

        ///
        async formSubmit() {

            //var data = [];
            var dataTennisCourtOpeningHour = [];
            var dataCart = [];

            console.log('cmaou o submit');

            var inputs = document.querySelectorAll('input[type="checkbox"]:checked')
            var modalErrorTop = document.querySelector('.modal-error-top');
            var modalErrorBotton = document.querySelector('.modal-error-bottom');
            modalErrorTop.classList.add('d-none');
            modalErrorBotton.classList.add('d-none');

            if (!inputs.length) {
                modalErrorTop.classList.remove('d-none');
                modalErrorBotton.classList.remove('d-none');

                // remove lentamente a msg de erro
                setInterval(function () {
                    if (!modalErrorTop.style.opacity) {
                        modalErrorTop.style.opacity = 1;
                        modalErrorBotton.style.opacity = 1;
                    }
                    if (modalErrorTop.style.opacity > 0) {
                        modalErrorTop.style.opacity -= 0.1;
                        modalErrorBotton.style.opacity -= 0.1;
                    } else {
                        modalErrorTop.classList.add('d-none');
                        modalErrorBotton.classList.add('d-none');
                        clearInterval(this);
                    }
                }, 500);

                return;
            }

            for (const [, value] of Object.entries(inputs)) {

                // console.log('this.getTennisCourtOpeningHourItemsToTennisCourtId.data', this.getTennisCourtOpeningHourItemsToTennisCourtId.data);
                // console.log('this.getTennisCourtOpeningHourItemsToTennisCourtId.data >>>>>>>> ', this.getTennisCourtOpeningHourItemsToTennisCourtId.data[value.value]);

                var getTennisCourtOpeningHourItemsToTennisCourtId = this.getTennisCourtOpeningHourItemsToTennisCourtId.data[value.value];

                //console.log('getTennisCourtOpeningHourItemsToTennisCourtId >>>>>>>> ', getTennisCourtOpeningHourItemsToTennisCourtId.tennis_court_id);

                //data.push(this.getTennisCourtOpeningHourItemsToTennisCourtId.data[value.value]);
                var time_start = moment(value.dataset.date).toDate();
                dataTennisCourtOpeningHour.push({
                    name: getTennisCourtOpeningHourItemsToTennisCourtId.tennis_court.name,
                    tennis_court_id: getTennisCourtOpeningHourItemsToTennisCourtId.tennis_court_id,
                    time_start: moment(time_start).format('YYYY-MM-DD HH:mm'),
                    time_end: moment(time_start).add(1, 'h').format('YYYY-MM-DD HH:mm'),
                    // price: value.price,
                    // price_promo: value.price_promo ?? null,
                });

                dataCart.push({
                    //tennis_court_calendar_id
                    //product_id
                    //product_name
                    qty: 1,
                    price: getTennisCourtOpeningHourItemsToTennisCourtId.price,
                    price_promo: getTennisCourtOpeningHourItemsToTennisCourtId.price_promo ?? null,
                    client_id: getTennisCourtOpeningHourItemsToTennisCourtId.user_id
                });

                //console.log('data', data);
            }

            console.log('data', { tennisCourtOpeningHour: dataTennisCourtOpeningHour, cart: dataCart });

            await Api.create('cart/add-item-tennis-court', { tennisCourtOpeningHour: dataTennisCourtOpeningHour, cart: dataCart }).then((r) => {
                if (r.success) {
                    this.closeModal();
                    this.$router.push({ name: 'dashboardCartShow' });
                }
                //console.log(r) 
            })

            return;

            //console.log('inputs', inputs);
            // Api.create('tennis-court-calendar', { id: this.tennisCourtId, ...this.register }).then((response) => {
            //     console.log('TennisCourtCalendarDatesComponent', response);
            // })
        },

        ///
        getActualDate() {
            const d = new Date();

            this.actualDay = d.getDay();
            this.actualMonth = this.month[this.today.getMonth()];
            this.actualYear = d.getFullYear();
        },

        ///
        getDaysInMonth(month, year) {
            var date = new Date(year, month, 1);
            var days = [];
            while (date.getMonth() === month) {
                days.push(new Date(date));
                date.setDate(date.getDate() + 1);
            }
            return days;
        },

        ///
        next() {
            // this.currentYear = (this.currentMonth === 11) ? this.currentYear + 1 : this.currentYear;
            // this.currentMonth = (this.currentMonth + 1) % 12;

            // this.currentDay = Number(moment().format('DD')); //this.today.getMonth();
            // this.currentMonth = moment().format('MM'); //this.today.getMonth();
            // this.currentYear = Number(moment().year());//this.today.getFullYear();

            let d = moment(this.currentYear + '-' + this.currentMonth + '-' + this.currentDay).add(1, 'months');
            this.currentYear = d.year();
            this.currentMonth = d.format('MM');
            this.showCalendar(this.currentMonth, this.currentYear);
        },

        ///
        previous() {
            let d = moment(this.currentYear + '-' + this.currentMonth + '-' + this.currentDay).subtract(1, 'months');
            this.currentYear = d.year();
            this.currentMonth = d.format('MM');
            this.showCalendar(this.currentMonth, this.currentYear);
        },

        ///
        jump() {
            this.currentYear = parseInt(this.selectYear.value);
            this.currentMonth = parseInt(this.selectMonth.value);
            this.showCalendar(this.currentMonth, this.currentYear);
        },

        ///
        async broadcastTennisCourtCalendarForDateAndTennisCourtId() {

            if (!this.tennisCourtCalendarForTennisCourtId?.data[0]) {
                return;                
            }

            let time = moment(this.tennisCourtCalendarForTennisCourtId?.data[0].time_start);
        
            var varLaravelEcho = LaravelEcho.auth();
            
            console.log('tennis-court-calendar-for-date-and-tennis-court-id.' + this.tennisCourtId + time.format('YYYYMMDDHHmm'));


            varLaravelEcho.private('tennis-court-calendar-for-date-and-tennis-court-id.' + this.tennisCourtId + '.' + time.year() + '.' + time.format('MM'))
                .listen('TennisCourtCalendarForDateAndTennisCourtIdEvent', async (response) => {

                    //console.log('TennisCourtCalendarForDateAndTennisCourtIdEvent', response, this.tennisCourtId + time.format('YYYYMMDDHHmm'));

                    for (const [, value] of Object.entries(response)) {
                      console.log(value.time_start, moment(value.time_start).format('YYYYMMDDHHmm'));

                      const el = document.getElementById(this.tennisCourtId + moment(value.time_start).format('YYYYMMDDHHmm'));
                      //el.setAttribute('style', 'background-color:red !important');
                      el.firstChild.innerHTML += '<i class="bi bi-cart3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Seja Mais Rápido, temos outras pessoas reservando: '+ Object.keys(response).length+'"></i>';
                    }
                    // if (response.status == 'paid') {
                    //   return
                    // }

                    // if (response.status == 'awaiting_payment') {
                    //   //await commit('SET_CHECK_PAYMENT_IS_MADE', true);  
                    //   await commit('SET_CHECK_PAYMENT_IS_MADE', true);
                    //   await commit('SET_CUSTUMER_REQUEST', response);
                    //   console.log('getters.getCustomerRequest', getters.getCustomerRequest);
                    //   return
                    // }
                });
        },
    }
}
</script>

<style>
.tcc-cell {
    background: #fff !important;
}

.tcc-cell-empty {
    background: #f9f9f9 !important;
}

.tcc-cell:hover,
.tcc-cell-empty:hover {
    background: greenyellow !important;
    transition: transform 0.5s, filter 0.5s ease-in-out;
    transform-origin: center center;
    filter: brightness(80%);
}

.tcc-cell-disabled {
    cursor: not-allowed;
    background-color: #cccccc !important;
    color: black !important;
}
</style>