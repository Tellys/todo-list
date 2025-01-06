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
                    <div class="row border-bottom py-2">
                        <div class="col text-center fw-bolder"></div>
                        <div class="col text-center fw-bolder">Hora Início</div>
                        <div class="col text-center fw-bolder">Hora Fim</div>
                        <div class="col text-center fw-bolder">Preço</div>
                    </div>

                    <form id="tciclModalForm">
                        <div id="tciclModalTbody"></div>
                    </form>

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
import { mapActions, mapGetters } from 'vuex';
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

            tennisCourtId: this.$route.params.id,

            listClassDefaultTennisCourtCalendarLine: ['tennis-court-calendar-line-hour', 'row', 'border-bottom', 'py-2'],

            // para o caso de a lista não ter nenhum check, mas o carrinho ter compras
            enableRouteToCart: false,

            //vamos precisar para varios casos abaixo dos dados do userLoggedIn
            getUserLoggedIn: null,

        }
    },
    mounted() {
        this.today = moment().format('YYYY-MM-DD');//new Date(); //

        this.currentDay = moment().format('DD'); //this.today.getMonth();
        this.currentMonth = moment().format('MM'); //this.today.getMonth();
        this.currentYear = moment().format('YYYY');//this.today.getFullYear();

        this.monthAndYear = document.getElementById("monthAndYear");
        //this.selectYear = document.getElementById("year");
        //this.selectMonth = document.getElementById("month");

        this.init();

        // var inputs = document.querySelectorAll('input[type="checkbox"]:checked')
        // inputs.forEach(function(e){
        //     e.addEventListener('click', function() {
        //         console.log('pegou os check');
        //     })
        // })

        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
            // delay: {
            //     show: 500,
            //     hide: 100,
            // },
        });

        // this.$nextTick(function() {
        //     this.$refs.deleteItemToCart.addEventListener('click', (e) => {
        //         console.log('e',e);
        //     })
        // });

        this.enableRouteToCart = false;

        // nos casos de refresh a modal estava ficando aberta
        this.closeModal();
    },
    computed: {
        ...mapGetters('tennisCourtCalendar', ['tennisCourtCalendarForTennisCourtId', 'numberOfHoursPerDay', 'bgColerHourUnavailable']),
        ...mapGetters('tennisCourtOpeningHour', ['getTennisCourtOpeningHourItemsToTennisCourtId']),
        ...mapGetters('login', ['loggedInUserId']),
    },
    methods: {
        ...mapActions('tennisCourtCalendar', ['deleteItemOfCartConfirm', 'getItemsForTennisCourtId']),

        ///
        async updateData() {
            await this.getItemsForTennisCourtId(this.$route.params.id);
            await store.dispatch('tennisCourtCalendar/numberOfHoursPerDay', this.$route.params.id);
        },

        ///
        async init() {
            await this.updateData();
            await this.showCalendar(this.currentMonth, this.currentYear);
        },

        ///
        async onClikday() {
            document.querySelectorAll('.active-modal').forEach(el => {
                el.addEventListener('click', (e) => {
                    console.log('clicou no dia')
                    this.showModal(e)
                });
            })
        },

        ///
        async showModal(el) {

            //console.log('el.target', el.target);

            await this.updateData();

            var d = moment(el.target.id);
            this.selectedDay = d;

            var dTarget = [Number(d.format('YYYY')), d.format('MM'), d.format('DD')];

            //console.log('el.target.id', el.target);
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
            //console.log('this.tennisCourtCalendarForTennisCourtId.data lll', this.tennisCourtCalendarForTennisCourtId.data[0]);
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

                        myRenderTbody += '<div class="' + this.listClassDefaultTennisCourtCalendarLine.join(' ') + '" id="' + this.tennisCourtId + trTagId + '" ' + roleButton + '>';
                        myRenderTbody += '<div class="col">';
                        myRenderTbody += '<div id="modal-' + el.target.id + '" ' + cellStyle + ' class="tag-to-render-input-check float-start">';
                        myRenderTbody += myRenderTbodyInputForm;
                        myRenderTbody += '</div>';
                        myRenderTbody += '<div class="tag-to-render-icon d-none float-start"></div>';
                        myRenderTbody += '</div>';
                        myRenderTbody += '<div class="col text-center" ' + trOnclick + ' ' + cellStyle + '>' + hourStart + ':00</div>';
                        myRenderTbody += '<div class="col text-center" ' + trOnclick + ' ' + cellStyle + '>' + hourEnd + ':00</div>';
                        myRenderTbody += '<div class="col text-center" ' + trOnclick + ' ' + cellStyle + '>R$ ' + value.price + '</div>';
                        myRenderTbody += '</div>';
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

            // atenção para a sequencia das fun abaixo, o broadcast tem q ser o último
            await this.addAlertItemPurchased();
            await this.broadcastTennisCourtCalendarForDateAndTennisCourtId();
        },

        ///
        closeModal() {
            this.uniqueModal?.hide();
        },

        ///
        async showCalendar(month, year) {

            //month = Number(month);

            // pega o numero do dia da semana do primeiro dia do mês em tela ex:'1 = segunda'
            let firstDay = moment(year + '-' + month + '-' + 1).format('e');
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

                    // set the current day
                    let curretDay = moment(year + '-' + month + '-' + date);

                    // verifica se o dia é valido ex; '31/02 return false'
                    if (!curretDay.isValid()) {
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
                        cell.setAttribute("id", `${year}${month}${(date < 10 ? '0' : '') + date}`);
                        cell.classList.add("cell-calendar");

                        //se a data for maior ou igual ao dia atual
                        // necessário para deshabilitar data anterior ao dia atual
                        //console.log("moment().isSameOrBefore(year+'-'+month+'-'+date)", moment().format('YYYY-MM-DD'),year+'-'+month+'-'+date,  moment().isSameOrBefore(year+'-'+month+'-'+date, 'day'));
                        if (moment().isSameOrBefore(year + '-' + month + '-' + date, 'day')) {
                            cell.setAttribute("role", 'button');
                            cell.classList.add("tcc-cell");
                            // to active Modal onClick
                            cell.classList.add("active-modal");
                        } else {
                            cell.classList.add("tcc-cell-disabled");
                        }

                        // color today's date
                        if (moment().isSame(year + '-' + month + '-' + date, 'day')) {
                            cell.classList.remove("tcc-cell");
                            cell.classList.add("border-5","border-info");
                            //cell.classList.add("bg-secondary");
                        }

                        cell.appendChild(cellText);
                        row.appendChild(cell);

                        date++;
                    }
                }
                if (tbl) {
                    tbl.appendChild(row); // appending each row into calendar body.
                }
            }

            // quando for clicada a celular
            await this.onClikday();
            // para colorir celulas com horarios ocupados
            await this.colorCalendarCell();

        },

        ///
        async colorCalendarCell(obj = null) {

            let i = 0;
            let thisStyle = '';
            let numberOfHoursPerDay = this.numberOfHoursPerDay.data;

            for (const [, value] of Object.entries(obj ?? this.tennisCourtCalendarForTennisCourtId.data)) {

                const timeStart = moment(value.time_start);

                if (!timeStart.isValid()) {
                    return
                }

                let bgColor = '#ffffff';
                let bgColerHourUnavailable = this.bgColerHourUnavailable;

                // se a data for menor que a data atual, a cor muda para o padrão de datas passadas
                if (!moment().isSameOrBefore(timeStart, 'day')) {
                    bgColor = '#cccccc';
                }

                // se a data estiver reservada para o user que esta logado a cor muda
                let userLoggedIn = await Api.userLoggedIn();
                if (value.user_id == userLoggedIn.id) {
                    bgColerHourUnavailable = 'rgb(21, 115, 71)';
                }

                switch (value.status) {
                    case 'paid':

                        if (i == 0) {
                            numberOfHoursPerDay = 100 / numberOfHoursPerDay[(timeStart.format('dddd')).toLowerCase()];
                            i = numberOfHoursPerDay;
                            thisStyle = ' ' + bgColerHourUnavailable + ' 0%, ' + bgColerHourUnavailable + '  ' + i + '%,' + bgColor + ' ' + i + '%, ';
                        }else{
                            thisStyle += '  ' + bgColerHourUnavailable + '  ' + i + '%, ' + bgColerHourUnavailable + '  ' + (i + numberOfHoursPerDay) + '%, ' + bgColor + ' ' + (i + numberOfHoursPerDay) + '%, ';
                            i = i + numberOfHoursPerDay;
                        }

                        var cell = document.getElementById(timeStart.format('YYYYMMDD'));

                        if (cell) {
                            cell.classList.add("text-center");
                            cell.setAttribute("style", "background-image: linear-gradient(to right, " + thisStyle + " " + bgColor + " 100%) !important");
                        }

                        break;

                    default:
                        break;
                }
            }
            return;
        },

        ///
        async formSubmit() {

            //var data = [];
            var dataTennisCourtOpeningHour = [];
            var dataCart = [];

            console.log('chamou o submit');

            var inputs = document.querySelectorAll('input[type="checkbox"]:checked')
            var modalErrorTop = document.querySelector('.modal-error-top');
            var modalErrorBotton = document.querySelector('.modal-error-bottom');
            modalErrorTop.classList.add('d-none');
            modalErrorBotton.classList.add('d-none');
            modalErrorTop.style.opacity = "";
            modalErrorBotton.style.opacity = "";

            if (!inputs.length) {

                // para o caso de a lista não ter nenhum check, mas o carrinho ter compras
                if (this.enableRouteToCart) {
                    this.$router.push({ name: 'dashboardCart' })
                    this.closeModal();
                    return;
                }

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
                }, 2000);

                return;
            }

            let userLoggedIn = await Api.userLoggedIn();

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
                    client_id: userLoggedIn.id,
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

            if (!this.selectedDay) {
                return;
            }

            let time = moment(this.selectedDay);

            //console.log('let time', this.selectedDay);

            var varLaravelEcho = LaravelEcho.auth();

            //console.log('tennis-court-calendar-for-date-and-tennis-court-id.' + this.tennisCourtId + '.' + time.format('YYYY') + '.' + time.format('MM'));

            varLaravelEcho.private('tennis-court-calendar-for-date-and-tennis-court-id.' + this.tennisCourtId + '.' + time.format('YYYY') + '.' + time.format('MM'))
                .listen('TennisCourtCalendarForDateAndTennisCourtIdEvent', async (response) => {
                    console.log('broadcastTennisCourtCalendarForDateAndTennisCourtId', response);
                    store.commit('tennisCourtCalendar/SET_ITEMS_FOR_TENNIS_COURT_ID', response)
                    await this.addAlertItemPurchased(response);
                });
        },

        ///
        async addAlertItemPurchased(obj = null) {

            //console.log('addAlertItemPurchased', obj);
            //limpa todas as cleasses css
            const allEl = document.querySelectorAll('.' + this.listClassDefaultTennisCourtCalendarLine[0]);
            allEl.forEach(el => {
                el.setAttribute('class', '');
                // add defult list class
                el.classList.add(...this.listClassDefaultTennisCourtCalendarLine);
            });

            // rollback icons
            document.querySelectorAll('.tag-to-render-icon').forEach(el => {
                el.classList.add('d-none');
                el.innerHTML = '';
            });

            // rollback input check
            document.querySelectorAll('.tag-to-render-input-check').forEach(el => {
                el.classList.remove('d-none');
            });


            //console.log('this.tennisCourtCalendarForTennisCourtId.data',this.tennisCourtCalendarForTennisCourtId.data);

            for (const [, value] of Object.entries(obj ?? this.tennisCourtCalendarForTennisCourtId?.data)) {

                // pega a tag do elemento
                let tagId = moment(value.time_start).format('YYYYMMDDHHmm');
                const el = document.getElementById(this.tennisCourtId + tagId);

                //
                if (el) {
                    //value.status == 'paid'
                    if (value.status == 'paid') {
                        let userLoggedIn = await Api.userLoggedIn();
                        if (userLoggedIn.id == value.user_id) {
                            this.setPropElementFunctionAddAlertItemPaidLoggedInUser(el);
                        } else {
                            this.setPropElementFunctionAddAlertItemPaid(el);
                        }
                    }

                    //value.status == 'awaiting_payment'
                    if (value.status == 'awaiting_payment') {
                        let userLoggedIn = await Api.userLoggedIn();
                        if (userLoggedIn.id == value.user_id) {
                            this.setPropElementFunctionAddAlertItemAwaitingPaymentLoggedInUser(el, value.user_id);
                        } else {
                            //console.log(el, value.status); loggedInUser
                            this.setPropElementFunctionAddAlertItemAwaitingPayment(el);
                        }
                    }

                    //value.status == 'in_cart'
                    if (value.status == 'in_cart') {
                        let userLoggedIn = await Api.userLoggedIn();
                        if (userLoggedIn.id == value.user_id) {
                            this.setPropElementFunctionAddAlertItemAddedToCartLoggedInUser(el, value.user_id);
                        } else {
                            //console.log(el, value.status); loggedInUser
                            this.setPropElementFunctionAddAlertItemAddedToCart(el);
                        }

                        this.enableRouteToCart = true;
                    }
                }
            }
        },

        /// Paid LoggedInUser
        async setPropElementFunctionAddAlertItemPaidLoggedInUser(el) {
            for (const child of el.children) {
                child.removeAttribute('onClick')
            }
            el.setAttribute('style', 'cursor: not-allowed;');
            el.setAttribute('role', '');
            el.classList.add('bg-success', 'text-white', 'disabled', 'checked', 'text-decoration-line-through', 'fst-italic');
            el.dataset.bsCustomClass = "custom-tooltip-secondary-subtle"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Este horário esta reservado para você!";

            //retira o input check impossibilitando a seleção do item
            el.querySelector('.tag-to-render-input-check').innerHTML = '<i class="bi bi-check2-square"></i> ';
        },

        /// Paid
        async setPropElementFunctionAddAlertItemPaid(el) {
            for (const child of el.children) {
                child.removeAttribute('onClick')
            }
            //el.setAttribute('style', 'background-color: ' + this.bgColerHourUnavailable + ' !important; cursor: not-allowed;');
            el.setAttribute('style', 'cursor: not-allowed;');
            //el.setAttribute('onClick', "alert('Já Ocupado')");
            el.setAttribute('role', '');
            el.classList.add('bg-danger-subtle', 'text-danger-emphasis', 'disabled', 'checked', 'text-decoration-line-through', 'fst-italic');
            el.dataset.bsCustomClass = "custom-tooltip-danger"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Outro cliente ja reservou e pagou";

            //retira o input check impossibilitando a seleção do item
            el.querySelector('.tag-to-render-input-check').innerHTML = '<i class="bi bi-cart-check-fill"></i>';
        },

        /// LoggedInUser
        async setPropElementFunctionAddAlertItemAwaitingPaymentLoggedInUser(el) {
            //el.querySelectorAll('input[type="checkbox"]:checked')
            //el.querySelector('.form-check-input').checked =true;
            //console.log('.form-check-input', el.querySelector('.form-check-input'))
            el.classList.add('bg-success-subtle', 'text-success-emphasis');
            el.dataset.bsCustomClass = "custom-tooltip-success"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Voce selecionou. Estará garantido após o pagamento";

            //el.querySelector('.tag-to-render-icon').innerHTML = '<i class="bi bi-cart-plus text-warning-emphasis" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Seja Mais Rápido, temos outras pessoas selecionando"></i>';
            //el.querySelector('.tag-to-render-input-check').innerHTML = '<i class="bi bi-x-lg text-danger me-3 delete-item-of-cart" data-id="'+tennisCourtCalendarForTennisCourtId+'"></i><i class="bi bi-cart-plus"></i>';
            let tagToRenderIcon = el.querySelector('.tag-to-render-icon');
            tagToRenderIcon.innerHTML = '<i class="bi bi-cart-plus"></i>';
            tagToRenderIcon.classList.toggle('d-none')

            el.querySelector('.tag-to-render-input-check').classList.toggle('d-none');
        },

        ///
        async setPropElementFunctionAddAlertItemAwaitingPayment(el) {
            el.classList.add('bg-info-subtle', 'text-info-emphasis');
            el.dataset.bsCustomClass = "custom-tooltip-warning"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Outro cliente esta em fase de pagamento!";

            let tagToRenderIcon = el.querySelector('.tag-to-render-icon');
            tagToRenderIcon.innerHTML = '<span class="hourglass-animate fa-stack"><i class="fa fa-stack-1x fa-hourglass-start"></i><i class="fa fa-stack-1x fa-hourglass-half"></i><i class="fa fa-stack-1x fa-hourglass-end"></i><i class="fa fa-stack-1x fa-hourglass-end"></i><i class="fa fa-stack-1x fa-hourglass-o"></i></span>';
            tagToRenderIcon.classList.toggle('d-none')

            el.querySelector('.tag-to-render-input-check').classList.toggle('d-none');
        },

        /// LoggedInUser
        async setPropElementFunctionAddAlertItemAddedToCartLoggedInUser(el) {
            //el.querySelectorAll('input[type="checkbox"]:checked')
            //el.querySelector('.form-check-input').checked =true;
            //console.log('.form-check-input', el.querySelector('.form-check-input'))
            el.classList.add('bg-success-subtle', 'text-success-emphasis');
            el.dataset.bsCustomClass = "custom-tooltip-success"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Voce selecionou. Estará garantido após o pagamento";

            //el.querySelector('.tag-to-render-icon').innerHTML = '<i class="bi bi-cart-plus text-warning-emphasis" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Seja Mais Rápido, temos outras pessoas selecionando"></i>';
            //el.querySelector('.tag-to-render-input-check').innerHTML = '<i class="bi bi-x-lg text-danger me-3 delete-item-of-cart" data-id="'+tennisCourtCalendarForTennisCourtId+'"></i><i class="bi bi-cart-plus"></i>';

            let tagToRenderIcon = el.querySelector('.tag-to-render-icon');
            tagToRenderIcon.innerHTML = '<i class="bi bi-cart-plus"></i>';
            tagToRenderIcon.classList.toggle('d-none')

            el.querySelector('.tag-to-render-input-check').classList.add('d-none');
        },

        ///
        async setPropElementFunctionAddAlertItemAddedToCart(el) {
            el.classList.add('bg-warning-subtle', 'text-warning-emphasis');
            el.dataset.bsCustomClass = "custom-tooltip-warning"
            el.dataset.bsToggle = "tooltip";
            el.dataset.bsPlacement = "top";
            el.dataset.bsTitle = "Seja Mais Rápido, temos outras pessoas selecionando";

            let tagToRenderIcon = el.querySelector('.tag-to-render-icon');
            tagToRenderIcon.innerHTML = '<i class="fa-solid fa-fire fa-beat-fade" style="--fa-beat-fade-opacity: 0.1; --fa-beat-fade-scale: 1.25;"></i>';
            tagToRenderIcon.classList.toggle('d-none')

            el.querySelector('.tag-to-render-input-check').classList.remove('d-none');
        },

        async userLoggedIn() {
            return this.getUserLoggedIn = this.getUserLoggedIn ?? await Api.userLoggedIn();
        }

    }
}
</script>
<style lang="scss">
// Modify These Values ( Use Even Numbers or Decimals )
$AnimationSpeed: 0.6;
//$Font-size: 20rem;
//$Colors: #ffc400, #ffc400, #ffab00, #ffc400, #006064;
$Colors: #024dbc, #024dbc, #024dbc, #024dbc, #fff;

// End of modify
/* */
/* Animation */
/* */
.hourglass-animate {
    opacity: 1;
    color: #6a1b9a;
    //font-size: $Font-size;
    $_animationSpin: hourglass-spin ($AnimationSpeed * 4s) ease-out infinite;

    i {
        opacity: 0;
        animation: hourglass ($AnimationSpeed * 4s) ease-in infinite,
            $_animationSpin;
    }

    @for $i from 1 through 4 {
        &>i:nth-child(#{$i}) {
            color: nth($Colors, $i);
            animation-delay: $AnimationSpeed * ($i - 1) * 1s, 0s;
        }
    }

    &>i:nth-child(4) {
        animation: hourglass-end ($AnimationSpeed * 4s) ease-in infinite,
            $_animationSpin;
    }

    &>i:nth-child(5) {
        color: nth($Colors, 5);
        opacity: 1;
        animation: $_animationSpin;
    }
}

@keyframes hourglass {
    0% {
        opacity: 1
    }

    24% {
        opacity: 0.9
    }

    26% {
        opacity: 0
    }
}

@keyframes hourglass-end {
    0% {
        opacity: 0
    }

    70% {
        opacity: 0
    }

    75% {
        opacity: 1
    }

    100% {
        opacity: 1
    }
}

@keyframes hourglass-spin {
    75% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(180deg);
    }
}
</style>

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