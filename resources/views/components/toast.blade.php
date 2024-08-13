<div x-data="{
    secuence: 0,
    messages: [],
    deleteMessage: function(id) {
        this.messages = this.messages.filter(function(message) {
            return message.id != id;
        });
    },
    addMessage: function(message) {
        this.messages.push({
            id: this.secuence++,
            title: message.title,
            message: message.message,
            type: message.type,
            duration: message.duration,
            timmer: null,
            show: true
        });
    },
    init() {
        Livewire.on('add-notification', (message) => {
            this.addMessage(message[0]);
        });
    }
}">
    <div x-show="messages.length > 0" class="fixed top-0 right-0 w-[400px] max-h-screen overflow-y-auto z-[9999999999999] scrollbar-hide overflow-x-hidden">
        <div class="px-5 pt-6 pb-8 w-full">
            <template x-for="message in messages" :key="message.id">
                <div class="pt-2 animate-right-entrance" x-init="message.timmer = setTimeout(() => {
                    message.show = false;
                    setTimeout(() => { deleteMessage(message.id); }, 500);
                }, message.duration)" x-show="message.show" x-transition:out.opacity.duration.500ms
                    x-transition:leave.opacity.duration.500ms>
                    <div :class="'rounded-xl flex items-center text-white w-full p-5 relative drop-shadow-lg ' +
                        (
                            message.type == 'error' ? 'bg-error' :
                            message.type == 'warning' ? 'bg-warning' :
                            message.type == 'info' ? 'bg-primary' : 'bg-success'
                        )">
                        <svg x-on:click="
                            clearTimeout(message.timmer);
                            message.show = false;
                            setTimeout(() => {deleteMessage(message.id);}, 500);" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" :class="'bg-white rounded-full w-5 h-5 absolute top-2 right-2 cursor-pointer ' +
                            (message.type == 'error' ? 'text-error' :
                                message.type == 'warning' ? 'text-warning' :
                                message.type == 'info' ? 'text-primary' :
                                'text-success')">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                        <div class="flex">
                            <div class="mr-3">
                                <div x-show="message.type == 'success'">
                                    <x-svg.success />
                                </div>
                                <div x-show="message.type == 'error'">
                                    <x-svg.error />
                                </div>
                                <div x-show="message.type == 'warning'">
                                    <x-svg.warning />
                                </div>
                                <div x-show="message.type == 'info'">
                                    <x-svg.info />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <h2 class="font-semibold text-lg" x-text="message.title"></h2>
                                <p class="text-sm" x-html="message.message"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
