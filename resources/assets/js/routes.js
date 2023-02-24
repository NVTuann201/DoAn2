import { endpointhttp } from "./env";

export const getpublishers = "publishers";
export const asynchronouspublishers = "asynchronous/publishers";
export const gettoppublishers = "toppublishers";
export const gettoppublishingofareas = "toppublishingofareas";
export const getpublishingofareas = "publishingofareas";
export const getdatasetresources = "datasetresources";
export const asyncdatasetresources = "asyncdatasetresources";
export const detaildataset = "/detail/dataset/";
export const detailpublisher = "/detail/publisher/";
export const getprotectedareas = "protectedareas";
export const gettopsubloc = "topsubloc";
export const getregion = "getregion";
export const getsubloc = "sublocs";
export const gettopdesig = "topdesig";
export const getdesigs = "desigs";
export const gettopstatusofprotectedareas = "protectedareas/topstatuses";
export const gettopgovtypesofprotectedareas = "protectedareas/topgovtypes";
export const asynchronousprotectedareas = "asynchronous/protectedareas";
export const detailprotectedarea = "/detail/protectedarea/";
export const getoccurrences = "occurrences";
export const gettoppublisersofoccurrences = "occurrences/toppublisers";
export const gettopdatasetsofoccurrences = "occurrences/topdatasets";
export const gettaxon = "taxon";
export const gettopdatasetfortaxon = "taxon/topdatasets";
export const dataresuorces = "taxon/datasetresources";
export const deleteorganizaition = "admin/organizations/delete/";
export const deleteprotectedarea = "admin/protectedareas/delete/";
export const deletespecies = "admin/species/delete/";
export const deletedataset = "admin/dataset/delete/";
export const gettoolspecies = "http://api.gbif.org/v1/species/search";
export const gettooloccurrence = "http://api.gbif.org/v1/occurrence/search";
export const getDataMap = "protectedareas/map";
export const getorganization_type = "api/organization-type";
export const getresource_type = "api/resource-type";

export const kingdom_url_get = endpointhttp + "admin/kingdom/info";
export const rank_url_get = endpointhttp + "admin/ranks/info";
export const phylum_url_get = endpointhttp + "admin/phylum/info";
export const class_url_get = endpointhttp + "admin/class/info";
export const order_url_get = endpointhttp + "admin/order/info";
export const family_url_get = endpointhttp + "admin/family/info";
export const genus_url_get = endpointhttp + "admin/genus/info";
export const species_url_get = endpointhttp + "admin/species/info";
export const subspecies_url_get = endpointhttp + "admin/subspecies/info";
export const dataset_resource_url_get = endpointhttp + "admin/dataset/info";

export const taxon_url_post = endpointhttp + "admin/taxon";

export const user_url_add = endpointhttp + "users/add";
export const user_url_update = endpointhttp + "users/update";

export const coso_role_get = endpointhttp + "roles/coso";

export const image_url = endpointhttp + "admin/images";
