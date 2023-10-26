function CreateRequest(method, url, payload, callback)
	--Подготавливаем запрос указывая метод и url
    local req = CreateHTTPRequestScriptVM(method, url)
	--Оборачиваем пейлоад в json и кладем его в тело запроса
	local pl = json.encode(payload)
	req:SetHTTPRequestRawPostBody('application/json', pl)
	--Устанавливаем ключ для валидации запроса на стороне сервера
    req:SetHTTPRequestHeaderValue("Dedicated-Server-Key", "test123")
	--Посылаем запрос
    req:Send(function(response)
		--Если требуется обработка колбека 
        if callback then
			--Разворачиваем ответ сервера и отдаём
            local object = json.decode(response.Body)
            callback(object)
        end
    end)
end
