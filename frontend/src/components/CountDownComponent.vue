<template>

    <div>
        <div class="progress shadow-sm" role="progressbar" id="progress_bar_backgroud" aria-label="" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress_bar" style="width: 100%">
            </div>
        </div>
        <div class="text-dark" id="progress_bar_text">{{ textDateExpiration }}</div>
    </div>

</template>

<script>
export default {
    name: 'CountDownComponent',
    data() {
        return {
            diffDateExpires: 0,
        }
    },
    props: {
        textDateExpiration: {
            type: String,
            default: 'Expirado',
        },
        constValueProgress: {
            type: Number,
            default: 3600 //1h
        },
        dateStart: {
            default: new Date()
        },
        dateEnd: {
            default: null
        },
    },

    mounted() {
        this.init();
    },
    methods: {
        async init() {
            this.countDownValidate();
        },

        countDownValidate() {
            var timeleft = 0;
            var horas = 0;
            var minutos = 0;
            var segundos = 0;
            var constValueProgress = this.constValueProgress;

            var percentage = 0;
            var progressBar = document.getElementById("progress_bar");
            var progressBarText = document.getElementById("progress_bar_text");
            var progressBarBackgroud = document.getElementById("progress_bar_backgroud");

            if (this.dateEnd) {

                // var diffMs = (new Date(this.item[0]?.expires_in) - new Date()); // milliseconds between now & Christmas
                // var diffDays = Math.floor(diffMs / 86400000); // days
                // var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
                // var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
                // console.log(diffDays + " days, " + diffHrs + " hours, " + diffMins + " minutes until Christmas =)");

                this.diffDateExpires = new Date(this.dateEnd) - this.dateStart;
                timeleft = Math.floor(this.diffDateExpires / 1000);
            }


            var downloadTimer = setInterval(function () {
                if (timeleft <= 0) {
                    clearInterval(downloadTimer);
                    progressBarText.innerText = "Expirado";
                    //progressBarText.classList.add('bg-danger')
                    progressBar.classList.add('bg-danger')
                    return;
                }
                horas = Math.floor(timeleft / 3600);
                horas = horas < 10 ? '0' + horas : horas;
                minutos = Math.floor((timeleft % 3600) / 60);
                minutos = minutos < 10 ? '0' + minutos : minutos;
                segundos = timeleft % 60;
                segundos = segundos < 10 ? '0' + segundos : segundos;

                percentage = (timeleft / constValueProgress) * 100;
                progressBar.style.width = percentage + "%";
                progressBarBackgroud.ariaValuenow = percentage;
                progressBar.style = `${'width:' + percentage + '%'}`;
                progressBarText.innerText = `${horas}h ${minutos}m ${segundos}s`; //Math.round(percentage) + "%";

                timeleft -= 1;
            }, 1000);
        },
    }
}

</script>