@props(['name', 'options' => [], 'selected' => null, 'placeholder' => 'Semua', 'autosubmit' => false, 'nullable' => true])

<div x-data="{
        open: false,
        value: @js((string) ($selected ?? '')),
        options: @js($nullable ? ['' => $placeholder] + $options : $options),
        get label() { return this.options[this.value] ?? @js($placeholder) },
        select(val) {
            this.value = val;
            this.open = false;
            @if ($autosubmit)
                this.$nextTick(() => this.$el.closest('form')?.submit());
            @endif
        },
     }"
     @click.outside="open = false"
     class="relative">
    <input type="hidden" name="{{ $name }}" :value="value">

    <button type="button" @click="open = !open"
        class="w-full min-w-[9.5rem] flex items-center justify-between gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 shadow-sm hover:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 transition">
        <span x-text="label" class="truncate"></span>
        <svg class="w-4 h-4 text-slate-400 transition shrink-0" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="absolute z-20 mt-1.5 w-full min-w-[9.5rem] rounded-xl bg-white shadow-lg ring-1 ring-slate-900/10 py-1.5"
         style="display: none;">
        <template x-for="(text, val) in options" :key="val">
            <button type="button" @click="select(val)"
                class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm text-left transition"
                :class="value === val ? 'text-emerald-700 font-medium bg-emerald-50' : 'text-slate-600 hover:bg-slate-50'">
                <span x-text="text"></span>
                <svg x-show="value === val" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </button>
        </template>
    </div>
</div>
