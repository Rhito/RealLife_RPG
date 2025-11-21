@php
    $danhmuc_id=@$item->multi_cat;
//        $serviceIdsString=$result->bill->service_id;
                            if (!empty($danhmuc_id)) {
                            // Nếu có ký tự "|" trong chuỗi
                                if (strpos($danhmuc_id, '|') !== false) {
                                    $trimmedServiceIdsString = trim($danhmuc_id, '|');
                                    $serviceIdsArray = explode('|', $trimmedServiceIdsString);
                                } else {
                                    $serviceIdsArray = [$danhmuc_id];
                                }

                                // Truy vấn các dịch vụ từ cơ sở dữ liệu
                                $services = \App\Modules\Post\Models\Category::whereIn('id', $serviceIdsArray)->pluck('name')->toArray();
                                $servicesString = implode(' | ', $services);
                            }
@endphp
<div>{{$servicesString}}</div>