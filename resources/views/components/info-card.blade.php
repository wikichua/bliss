@props(['data'])
<div class="overflow-y-auto overflow-x-hidden sm:-mx-6 lg:-mx-8">
    <div class="inline-block min-w-full sm:px-6 lg:px-8">
      <div class="overflow-hidden block p-6 rounded-lg shadow-lg bg-white dark:bg-slate-900 max-w-full">
        <table class="min-w-full">
          <tbody>
            @foreach ($data as $key => $value)
            <tr class="bg-white dark:bg-slate-800 border-b transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700">
              <td class="text-sm text-gray-900 dark:text-gray-50 font-light px-6 py-4 whitespace-nowrap">
                {!! $key !!}
              </td>
              <td class="text-sm text-gray-900 dark:text-gray-50 font-light px-6 py-4 whitespace-nowrap">
                {!! $value !!}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
