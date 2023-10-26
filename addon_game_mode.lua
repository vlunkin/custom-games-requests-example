require("web_utils")

-- define host that will be used in requests
host = "http://d2.local"

if CAddonTemplateGameMode == nil then
	CAddonTemplateGameMode = class({})
end

function Activate()
	GameRules.AddonTemplate = CAddonTemplateGameMode()
	GameRules.AddonTemplate:InitGameMode()
end

function CAddonTemplateGameMode:InitGameMode()
	mode = GameRules:GetGameModeEntity()
	ListenToGameEvent("player_chat", Dynamic_Wrap(CAddonTemplateGameMode,"OnChat"), self)

end

function CAddonTemplateGameMode:OnChat(keys)
	local txt = string.lower(keys.text)
	local playerID = keys.playerid
	local hero = PlayerResource:GetSelectedHeroEntity(playerID)
	local callback

	if (txt == '-save') then
		local steamid = PlayerResource:GetSteamAccountID(playerID)
		-- get all items in mail slots
		items = GetItemList(hero)
		-- send POST request with items table without callback processing
		CreateRequest("POST", host.."/api/inventory?steamid="..steamid, items, nil)
		
	elseif (txt == '-load') then
		local steamid = PlayerResource:GetSteamAccountID(playerID)
		-- send GET request withcallback processing 
		local body = CreateRequest("GET", host.."/api/inventory?steamid="..steamid, nil, function(data) 
																									-- callback processing (data - response object)
																									local eslot = nil
																									for slot=0,5 do
																										-- remove anything in current slot
																										local iItem = hero:GetItemInSlot(slot)
																										if iItem then hero:RemoveItem(iItem) end
																								
																										-- add item to slot
																										local item = hero:AddItemByName(data['slot'..slot])

																										-- rearrange slot
																										if item then
																											if eslot and eslot~=slot then	
																												hero:SwapItems( eslot, slot )
																											end
																										elseif not eslot then
																											eslot = slot
																										end
																									end
																									end)
	end
 end


 function GetItemName(hero, slot)
    local item = hero:GetItemInSlot(slot)
    if item then
        local itemName = item:GetAbilityName()
        return itemName
    else
        return ""
    end
end

function GetItemList(hero)

    local item
    local itemTable = {}

    for i=0,5 do
        item = GetItemName(hero,i)
        table.insert(itemTable,i,item)
    end

    return itemTable
end
