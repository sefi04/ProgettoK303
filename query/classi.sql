SELECT classe.ID, classe.settore, docente.username 
FROM docente,insegna,classe 
WHERE docente.id=insegna.cod_docente
AND insegna.cod_classe=classe.ID
AND docente.username="grimaldi"