@props(['disabled' => false])
<div x-data="{
    datePickerOpen: false,
    datePickerValue: '',
    model: @entangle($attributes->wire('model')).live,
    datePickerFormat: 'D d M, Y',
    datePickerMonth: '',
    datePickerYear: '',
    datePickerDay: '',
    datePickerDaysInMonth: [],
    datePickerBlankDaysInMonth: [],
    datePickerMonthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Deciembre'],
    datePickerDays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Juéves', 'Viernes', 'Sábado'],
    datePickerDayAbbrevations: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    datePickerDayClicked(day) {
        let selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
        this.datePickerDay = day;
        this.datePickerValue = this.datePickerFormatDate(selectedDate);
        this.datePickerIsSelectedDate(day);
        this.model = this.datePickerFormatDateModel( selectedDate );
    },
    datePickerPreviousMonth(){
        if (this.datePickerMonth == 0) {
            this.datePickerYear--;
            this.datePickerMonth = 12;
        }
        this.datePickerMonth--;
        this.datePickerCalculateDays();
    },
    datePickerNextMonth(){
        if (this.datePickerMonth == 11) {
            this.datePickerMonth = 0;
            this.datePickerYear++;
        } else {
            this.datePickerMonth++;
        }
        this.datePickerCalculateDays();
    },
    datePickerIsSelectedDate(day) {
        const d = new Date(this.datePickerYear, this.datePickerMonth, day);
        return this.datePickerValue === this.datePickerFormatDate(d) ? true : false;
    },
    datePickerIsToday(day) {
        const today = new Date();
        const d = new Date(this.datePickerYear, this.datePickerMonth, day);
        return today.toDateString() === d.toDateString() ? true : false;
    },
    datePickerCalculateDays() {
        let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
        // find where to start calendar day of week
        let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
        let blankdaysArray = [];
        for (var i = 1; i <= dayOfWeek; i++) {
            blankdaysArray.push(i);
        }
        let daysArray = [];
        for (var i = 1; i <= daysInMonth; i++) {
            daysArray.push(i);
        }
        this.datePickerBlankDaysInMonth = blankdaysArray;
        this.datePickerDaysInMonth = daysArray;
    },
    datePickerFormatDate(date) {
        let formattedDay = this.datePickerDayAbbrevations[date.getDay()];
        let formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
        let formattedMonth = this.datePickerMonthNames[date.getMonth()];
        let formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
        let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
        let formattedYear = date.getFullYear();

        if (this.datePickerFormat === 'M d, Y') {
            return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
        }
        if (this.datePickerFormat === 'MM-DD-YYYY') {
            return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
        }
        if (this.datePickerFormat === 'DD-MM-YYYY') {
            return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
        }
        if (this.datePickerFormat === 'YYYY-MM-DD') {
            return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
        }
        if (this.datePickerFormat === 'D d M, Y') {
            return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
        }

        return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
    },
    datePickerFormatDateModel(date) {
        let formattedDay = this.datePickerDayAbbrevations[date.getDay()];
        let formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
        let formattedMonth = this.datePickerMonthNames[date.getMonth()];
        let formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
        let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
        let formattedYear = date.getFullYear();

        return `${formattedYear}/${formattedMonthInNumber}/${formattedDate}`;
    },
}" x-init="
        currentDate = new Date();
        if (datePickerValue) {
            currentDate = new Date(Date.parse(datePickerValue));
        }
        datePickerMonth = currentDate.getMonth();
        datePickerYear = currentDate.getFullYear();
        datePickerDay = currentDate.getDay();
        datePickerValue = datePickerFormatDate( currentDate );
        model = datePickerFormatDateModel( currentDate );
        datePickerCalculateDays();
    " x-cloak>
    <div class="relative">
        <input x-ref="datePickerInput" type="text" @click="datePickerOpen=!datePickerOpen" x-model="datePickerValue" x-on:keydown.escape="datePickerOpen=false" {{ $disabled ? 'disabled' : '' }} class="flex w-full focus:border-customPrimary rounded-md shadow-sm focus:ring-0 disabled:cursor-not-allowed disabled:bg-customBlack/10 hover:border-customPrimary {{ $errors->has($attributes->wire('model')->value) ? 'border-error' : 'border-customGray' }}" placeholder="Select date" readonly />
        <div
            x-show="datePickerOpen"
            x-transition
            @click.away="datePickerOpen = false"
            class="absolute top-0 left-0 max-w-lg p-4 mt-12 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800"></span>
                    <span x-text="datePickerYear" class="ml-1 text-lg font-normal text-gray-600"></span>
                </div>
                <div>
                    <button @click="datePickerPreviousMonth()" type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                        <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="datePickerNextMonth()" type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                        <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-7 mb-3">
                <template x-for="(day, index) in datePickerDayAbbrevations" :key="index">
                    <div class="px-0.5">
                        <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
                    </div>
                </template>
            </div>
            <div class="grid grid-cols-7">
                <template x-for="blankDay in datePickerBlankDaysInMonth">
                    <div class="p-1 text-sm text-center border border-transparent"></div>
                </template>
                <template x-for="(day, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                    <div class="px-0.5 mb-1 aspect-square">
                        <div
                            x-text="day"
                            @click="datePickerDayClicked(day)"
                            :class="{
                                'bg-customPrimary': datePickerIsToday(day) == true,
                                'text-gray-600 hover:bg-customPrimary/50': datePickerIsToday(day) == false && datePickerIsSelectedDate(day) == false,
                                'bg-customBlack text-white hover:bg-opacity-75': datePickerIsSelectedDate(day) == true
                            }"
                            class="flex items-center justify-center text-sm leading-none text-center rounded-full cursor-pointer h-7 w-7"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
