<button {{ $attributes->merge(['type' => 'submit', 'class' => ' bg-secondary_blue text-white text-sm rounded        p-1 h-auto  text-center hover:cursor-pointer
    ease-in delay-100  hover:-translate-z-1 hover:scale-[1.1]  duration-200   disabled:bg-gray-400']) }}>
    {{ $slot }}
</button>
