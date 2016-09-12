cd /home/petacl/peta.cl/html/up/magmi/preimport/intcomex && sed 's/^\xEF\xBB\xBF//' intcomex.csv > intcomex.CSV && perl intcomex.pl intcomex.CSV > ../../import/2_intcomex.csv 
cd /home/petacl/peta.cl/html/up/magmi/preimport/ingrammicro && sed 's/^\xEF\xBB\xBF//' ingrammicro.csv > ingrammicro.CSV && perl ingrammicro.pl ingrammicro.CSV > ../../import/3_ingrammicro.csv 
cd /home/petacl/peta.cl/html/up/magmi/preimport/tecnoglobal && sed 's/^\xEF\xBB\xBF//' tecnoglobal.csv > tecnoglobal.CSV && perl tecnoglobal.pl tecnoglobal.CSV > ../../import/1_tecnoglobal.csv 
cd /home/petacl/peta.cl/html/up/magmi/preimport/quintec && sed 's/^\xEF\xBB\xBF//' quintec.csv > quintec.CSV && perl quintec.pl quintec.CSV > ../../import/4_quintec.csv 
#cd /home/magmi.peta.cl/html/import/ && perl updatepcofertas.pl updateingrammicro.CSV > UPDATEpcofertas/Uingrammicro.csv 
#cd /home/magmi.peta.cl/html/import/ && perl updatepcofertas.pl updatetecnoglobal.CSV > UPDATEpcofertas/Utecnoglobal.csv 
#cd /home/magmi.peta.cl/html/import/ && perl updatepcofertas.pl updatequintec.CSV > UPDATEpcofertas/Uquintec.csv 
#cd /home/magmi.peta.cl/html/import/ && sed 's/^\xEF\xBB\xBF//' fullbhphotobom.CSV > updatebhphoto.CSV && perl updatelevel1.pl updatebhphoto.CSV > UPDATElevel1/Ubhphoto.csv
#cd /home/magmi.peta.cl/html/import/ && perl level1.pl updateintcomex.CSV > /home/level1.cl/html/magmi/import/Uintcomex.csv 
#cd /home/magmi.peta.cl/html/import/ && perl full_bhphoto_level1.pl fullbhphoto.CSV > /home/level1.cl/html/magmi/import/fullbhphoto.csv  #dejar este comentario - no borrar
#cd /home/magmi.peta.cl/html/import/ && perl level1.pl updateingrammicro.CSV > /home/level1.cl/html/magmi/import/Uingrammicro.csv 
#cd /home/magmi.peta.cl/html/import/ && perl level1.pl updatetecnoglobal.CSV > /home/level1.cl/html/magmi/import/Utecnoglobal.csv 
#cd /home/magmi.peta.cl/html/import/ && perl update_all.pl /home/petacl/peta.cl/html/var/pro20/quintecaccesorioss.csv > /home/petacl/peta.cl/html/var/pro20/updateall.csv
#cd /home/magmi.peta.cl/html/import/ && perl full.pl fullintcomex.CSV > FULL/Fintcomex.csv 
#cd /home/magmi.peta.cl/html/import/ && perl full.pl fullingrammicro.CSV > FULL/Fingrammicro.csv 
#cd /home/magmi.peta.cl/html/import/ && perl full.pl fulltecnoglobal.CSV > FULL/Ftecnoglobal.csv 
#cd /home/magmi.peta.cl/html/import/ && sed 's/^\xEF\xBB\xBF//' fullbhphotobom.CSV > fullbhphoto.CSV && perl full_bhphoto.pl fullbhphoto.CSV > FULL/Fbhphoto.csv 
#cd /home/magmi.peta.cl/html/import/ && perl full.pl fullquintec.CSV > FULL/Fquintec.csv 
################             WYPO      ##########################
#perl /home/petacl/www.wypo.cl/html/up/magmi/preimport/dimerc/dimerc.pl /home/petacl/www.wypo.cl/html/up/magmi/preimport/dimerc/dimerc.csv > /home/petacl/www.wypo.cl/html/up/magmi/import/2_dimerc.csv 
#cd /home/petacl/www.wypo.cl/html/up/magmi/preimport/maconline/ && sed 's/^\xEF\xBB\xBF//' maconline.CSV > maconline.csv && perl maconline.pl maconline.csv > ../../import/25_maconline.csv 
#cd /home/petacl/www.wypo.cl/html/up/magmi/preimport/casamusa/ && sed 's/^\xEF\xBB\xBF//' casamusa.CSV > casamusa.csv && perl casamusa.pl casamusa.csv > ../../import/26_casamusa.csv 
#cd /home/petacl/www.wypo.cl/html/up/magmi/preimport/prisa/ && sed 's/^\xEF\xBB\xBF//' prisa.CSV > prisa.csv && perl match.pl prisa.csv > prisa1.csv && perl prisa.pl prisa1.csv > ../../import/6_prisa.csv 
#perl /home/petacl/www.wypo.cl/html/up/magmi/preimport/mueblessur/mueblesur.pl /home/petacl/www.wypo.cl/html/up/magmi/preimport/mueblessur/mueblesur.csv > /home/petacl/www.wypo.cl/html/up/magmi/import/5_mueblesur.csv 
