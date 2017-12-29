-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2017 at 08:53 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hometuitiont`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `advanced_search_tuition` (IN `tuition_code_p` VARCHAR(100), IN `class_p` INT(11), IN `subject_p` INT(11), IN `categories_p` VARCHAR(100), IN `location_p` VARCHAR(100), IN `min_fee_p` INT(11), IN `max_fee_p` INT(11), IN `institute_p` VARCHAR(100), IN `gender_p` INT(11), IN `student_no_p` INT(11), IN `timing_p` VARCHAR(100), IN `duration_p` INT(11), IN `age_p` INT(11))  NO SQL
BEGIN   

SET @p2=',';
SET @t1='ITEMS';
CALL `CreateTempTable`(categories_p, @p2,@t1);

SET @p3=',';
SET @t2='Degree';
CALL `CreateTempTable`(location_p, @p3,@t2);

SET @p4=',';
SET @t3='Institutions';
CALL `CreateTempTable`(institute_p, @p4,@t3);

SELECT tuitions.`id`, `student_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_status_id`, 
`tuition_code`, `no_of_students`, `location_id`, `tuition_date`,`special_notes`,GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br>') as subjects,
GROUP_CONCAT(class_names separator ',')  AS class_name,locations,ci.class_ids
 FROM `tuitions`

LEFT JOIN (

SELECT concat(c.name,':',GROUP_CONCAT(DISTINCT(s.name) separator ', ')) AS class_subjects, tip.institute_id,
c.id as cid, s.id AS sid, td.tuition_id AS tid ,c.name as class_name,
GROUP_CONCAT(s.id separator ',') AS subject_id,GROUP_CONCAT(c.name separator ',') AS class_names,
GROUP_CONCAT(c.id separator ',') AS class_ids
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm ON csm.id = td.class_subject_mapping_id
LEFT JOIN classes c ON c.id = csm.class_id
LEFT JOIN subjects s ON s.id = csm.subject_id
LEFT JOIN tuition_institute_preferences tip ON tip.tuition_id = td.tuition_id

GROUP BY c.id, td.tuition_id

)ci ON tuitions.id = ci.tid


LEFT JOIN locations loc ON loc.id = tuitions.location_id
LEFT JOIN ITEMS I ON I.ID = tuitions.tuition_catefory_id
LEFT JOIN Degree D ON D.ID = tuitions.location_id
LEFT JOIN Institutions  ON Institutions.ID = ci.institute_id

WHERE 
tuitions.is_active=1 AND tuitions.is_approved=1

AND (tuition_code_p='' OR tuitions.tuition_code LIKE CONCAT('%',tuition_code_p,'%'))
AND (class_p =0 OR FIND_IN_SET(class_p, ci.class_ids) )
AND (subject_p =0 OR FIND_IN_SET(subject_p, ci.subject_id) )
AND (categories_p='' OR tuitions.tuition_catefory_id = I.ID)
AND (location_p='' OR tuitions.location_id = D.ID)
AND (min_fee_p ='' OR tuitions.tuition_final_fee BETWEEN min_fee_p AND max_fee_p)
AND (institute_p ='' OR ci.institute_id = Institutions.ID)
AND (gender_p ='' OR tuitions.teacher_gender = gender_p)
AND (student_no_p ='' OR tuitions.no_of_students = student_no_p)
AND (timing_p ='' OR tuitions.suitable_timings = timing_p)
AND (duration_p ='' OR tuitions.teaching_duration = duration_p)
AND (age_p ='' OR tuitions.teacher_age >= age_p)
GROUP by tuitions.id
ORDER BY tuitions.tuition_code;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `assign_tuition` (IN `teacher_id_p` INT(11), IN `tuition_id_p` INT)  NO SQL
BEGIN

declare LId INTEGER;
SELECT `location_id` INTO LId FROM `tuitions` WHERE id= tuition_id_p;

SELECT T.id as teacher_id,T.firstname,T.lastname,T.city, T.teacher_band_id,T.mobile1,subjects.name as subject_name,classes.name as class_name,T.email, lp.location_id,subjects.name, td.id as td_id,tuition_id_p FROM 
teachers T


INNER JOIN teacher_location_preferences lp on lp.location_id = LId AND T.id = lp.teacher_id
INNER JOIN tuition_details td on td.tuition_id = tuition_id_p
INNER JOIN class_subject_mappings csm on csm.id =  td.class_subject_mapping_id
INNER JOIN teacher_subject_preferences tp on tp.teacher_id = T.id AND tp.class_subject_mapping_id = csm.id

INNER JOIN subjects on subjects.id = csm.subject_id
INNER JOIN classes on classes.id = csm.class_id

WHERE T.id =teacher_id_p;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `auto_matched_tuitions` (IN `location_p` VARCHAR(100), IN `subject_p` VARCHAR(100), IN `institute_p` VARCHAR(100), IN `age_p` INT(11), IN `category_p` VARCHAR(100), IN `timing_p` VARCHAR(100), IN `minfee_p` INT(11), IN `maxfee_p` INT(11), IN `exp_p` INT(11), IN `gender_p` INT(11))  NO SQL
    DETERMINISTIC
BEGIN

SET @p1=',';
CALL `SplitString`(location_p, @p1);

SET @p2=',';
CALL `SplitStringDL`(subject_p, @p2);

SET @p3=',';
SET @t2='Institute';
CALL `CreateTempTable`(institute_p, @p3,@t2);

SET @p4=',';
SET @t3='Category';
CALL `CreateTempTable`(category_p, @p4,@t3);

Select tuitions.*, ci.class_name, locations,GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br>') as subjects
  
FROM tuitions

LEFT JOIN (

SELECT concat(c.name,':',GROUP_CONCAT(DISTINCT(s.name) separator ' ,')) AS class_subjects, tip.institute_id,
c.id as cid, s.id AS sid, td.tuition_id AS tid ,c.name as class_name,
GROUP_CONCAT(s.id separator ',') AS subject_id,GROUP_CONCAT(c.name separator ',') AS class_names,
GROUP_CONCAT(c.id separator ',') AS class_ids
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm ON csm.id = td.class_subject_mapping_id
LEFT JOIN classes c ON c.id = csm.class_id
LEFT JOIN subjects s ON s.id = csm.subject_id
LEFT JOIN tuition_institute_preferences tip ON tip.tuition_id = td.tuition_id
    
GROUP BY c.id, td.tuition_id

)ci ON tuitions.id = ci.tid


LEFT JOIN locations on locations.id = tuitions.location_id
LEFT JOIN ITEMS I ON I.ID = tuitions.location_id
LEFT JOIN Degree D ON D.ID = ci.subject_id
LEFT JOIN Institute  ON Institute.ID = ci.institute_id

LEFT JOIN (

SELECT tc.id as cid,Category.ID
FROM tuition_categories tc
LEFT JOIN Category ON Category.ID = tc.id

)tcategory ON tcategory.id = tuitions.tuition_catefory_id

WHERE (location_p='' OR tuitions.location_id = I.ID)
AND (subject_p ='' OR ci.subject_id = D.ID)
AND (institute_p ='' OR ci.institute_id = Institute.ID)
AND (category_p ='' OR tuitions.tuition_catefory_id = tcategory.ID)
AND (age_p ='' OR tuitions.teacher_age >= age_p)
AND (timing_p ='' OR tuitions.suitable_timings = timing_p)
AND (minfee_p ='' OR tuitions.tuition_final_fee BETWEEN minfee_p AND maxfee_p)
AND (exp_p ='' OR tuitions.experience = exp_p)
AND (gender_p ='' OR tuitions.teacher_gender = gender_p)

GROUP by tuitions.id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `copy_tuition` (IN `tuition_id_p` INT(11), IN `tuition_code_p` VARCHAR(255))  NO SQL
BEGIN

INSERT INTO tuitions( `student_id`, `tuition_catefory_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_status_id`, `tuition_code`, `no_of_students`,`tuition_fee`,`band_id`,`experience`,
`suitable_timings`,`teaching_duration`, `location_id`, `tuition_date`,`tuition_start_date`, `special_notes`,`take_note`, `address`, `contact_no`,`contact_no2`, `teacher_gender`, `teacher_age`, `institution`,`referrer_id`,  `details`, `contact_person`,`created_by`,`created_at`,`updated_by`,`updated_at`) 

SELECT `student_id`, `tuition_catefory_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_status_id`,  tuition_code_p, `no_of_students`,`tuition_fee`,`band_id`,`experience`,
`suitable_timings`,`teaching_duration`, `location_id`, `tuition_date`, `tuition_start_date`,`special_notes`,`take_note`, `address`, `contact_no`,`contact_no2`, `teacher_gender`, `teacher_age`, `institution`,`referrer_id`,  `details`, `contact_person`,`created_by`,`created_at`,`updated_by`,`updated_at`
FROM `tuitions` WHERE `id`=tuition_id_p;

SET @lastid = LAST_INSERT_ID();

INSERT INTO tuition_details(`tuition_id`, `class_subject_mapping_id`) 
SELECT @lastid, `class_subject_mapping_id` FROM `tuition_details` WHERE `tuition_id` = tuition_id_p;

INSERT INTO teacher_bookmarks(`tuition_id`, `teacher_id`) 
SELECT @lastid, `teacher_id`  FROM `teacher_bookmarks` WHERE `tuition_id` = tuition_id_p;

INSERT INTO tuition_labels(`label_id`, `tuition_id`) 
SELECT `label_id`, @lastid FROM `tuition_labels` WHERE `tuition_id` = tuition_id_p;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateTempTable` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1), IN `TempTable` VARCHAR(100))  NO SQL
BEGIN

	DECLARE Item           VARCHAR(100) ;
	DECLARE ItemList       VARCHAR(100) ;
	DECLARE DelimIndex     INT  ;
			
	SET @dropTable =
	CONCAT(' DROP TEMPORARY TABLE IF EXISTS ', TempTable);
	PREPARE stmt FROM @dropTable;
	EXECUTE stmt;
   
	SET @createTempTable =
	CONCAT(' CREATE TEMPORARY TABLE ', TempTable,'( ID INT NULL ) ENGINE=MEMORY ');
    PREPARE stmt FROM @createTempTable;
    EXECUTE stmt;
 
	SET ItemList = InputString;
	SET DelimIndex = INSTR(ItemList, pDelimiter);
      
	WHILE DelimIndex > 0
		
		DO	
			
			SET @insertTempTable =
			CONCAT(' INSERT INTO ', TempTable,'(ID) VALUES ( ',SUBSTRING(ItemList, 1, DelimIndex - 1),')');
			PREPARE stmt FROM @insertTempTable;
			EXECUTE stmt;

			SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);			
			SET DelimIndex = INSTR(ItemList, pDelimiter);
			
		END WHILE; 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sms_text` ()  NO SQL
SELECT tuitions.id as t_id,
GROUP_CONCAT(ci.class_subjects separator '\r\n') as subject, institute.institute_name,
locations.locations as location_name, gender.name as gender,suitable_timings,special_notes,
tuition_fee,tuition_max_fee,tb.name as band_name,teaching_duration
from tuitions 

LEFT JOIN (

SELECT concat(c.name,': ',GROUP_CONCAT(s.name separator ',')) as class_subjects, 
c.id, td.tuition_id as tid 
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
LEFT JOIN classes c on c.id = csm.class_id
LEFT JOIN subjects s on s.id = csm.subject_id

GROUP BY c.id, td.tuition_id

)ci on tuitions.id = ci.tid

LEFT JOIN (

SELECT  inst.name as institute_name,tip.tuition_id  FROM institutes inst
LEFT JOIN tuition_institute_preferences tip on tip.institute_id = inst.id
GROUP BY tip.tuition_id

)institute ON institute.tuition_id = tuitions.id

LEFT JOIN teacher_bands tb on tb.id = tuitions.band_id
LEFT JOIN locations on locations.id  = tuitions.location_id
LEFT JOIN gender on gender.id  = tuitions.teacher_gender
INNER JOIN tuition_globals tg on tg.tuition_id = tuitions.id

GROUP BY tuitions.id
ORDER BY tb.name ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `global_tuitions` ()  NO SQL
SELECT tuitions.`id`, `student_id`, `is_created_admin`, tuitions.`is_active`, tuitions.`is_approved`, tuitions.special_notes,
`tuition_code`, `no_of_students`, `location_id`, `tuition_date`,
GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br />') as subjects,tb.name as band_name,contact_no,contact_no2

FROM `tuitions`
 
INNER JOIN tuition_globals tg on tg.tuition_id = tuitions.id

LEFT JOIN (

SELECT concat(c.name,': ',GROUP_CONCAT(s.name separator ',')) as class_subjects, 
c.id, td.tuition_id as tid, td.teacher_id, td.class_subject_mapping_id as csmid,
td.id as tdid
 
FROM tuition_details td 
LEFT JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
LEFT JOIN classes c on c.id = csm.class_id
LEFT JOIN subjects s on s.id = csm.subject_id

GROUP BY c.id, td.tuition_id

)ci on tuitions.id = ci.tid

LEFT JOIN teacher_bands tb on tb.id = tuitions.band_id
LEFT JOIN teacher_qualifications tq on tq.teacher_id = ci.teacher_id


GROUP by tuitions.id
ORDER BY tb.name ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `load_teachers` (IN `first_name` VARCHAR(100), IN `zip_code_p` VARCHAR(100), IN `teacher_band` INT(11), IN `marital_status` INT(11), IN `gender` INT(11), IN `father_name` VARCHAR(100), IN `minimum_fee` INT(11), IN `cnic_number_p` VARCHAR(100), IN `mobile_number` VARCHAR(100), IN `city_p` VARCHAR(100), IN `province_p` VARCHAR(100), IN `email_p` VARCHAR(100), IN `dg_level_p` VARCHAR(100), IN `subject_p` VARCHAR(100), IN `locs_p` VARCHAR(100), IN `is_active_p` INT(11), IN `is_approved_p` INT(11), IN `label_p` VARCHAR(100), IN `age_p` INT(11), IN `exp_p` VARCHAR(100), IN `reg_number_p` VARCHAR(100), IN `teacher_id_p` INT(11), IN `category_p` VARCHAR(100), IN `qualification_p` VARCHAR(100), IN `institute_p` VARCHAR(100), IN `timing_p` VARCHAR(100))  NO SQL
    DETERMINISTIC
BEGIN

SET @p1=',';
CALL `SplitString`(locs_p, @p1);

SET @p2=',';
CALL `SplitStringS`(subject_p, @p2);

SET @p3=',';
CALL `SplitStringDL`(dg_level_p, @p3);

SET @p4=',';
CALL `SplitStringLabels`(label_p, @p4);

SET @p5=',';
CALL `TeacherTuitionCategory`(category_p, @p5);

SET @p6=',';
SET @t6='TQUAL';
CALL `CreateTempTable`(qualification_p, @p6,@t6);

SET @p7=',';
SET @t7='INST';
CALL `CreateTempTable`(institute_p, @p7,@t7);

SELECT teachers.`id`, `user_id`, `teacher_band_id`, `marital_status_id`, `gender_id`, `firstname`, `lastname`, `fullname`, 
`registeration_no`, `father_name`, `expected_minimum_fee`, `expected_max_fee`, `religion`, `strength`, `no_of_children`, `cnic_number`, `strength`
`cnic_front_image`, `cnic_back_image`, `email`, `password`, `dob`, `landline`, `mobile1`,`personal_contactno2`, `mobile2`, `address_line1`,
 `age`,`address_line2`, `city`, `province`, `zip_code`, `country`, `other_detail`,`is_active`,`is_approved`,tb.name as band_name,
 `experience`, DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365 as agey,`teacher_photo`,
GROUP_CONCAT(DISTINCT(tq.qualification_name) separator ',') as qualifications,GROUP_CONCAT(DISTINCT(location_pref.zlocations) separator ',') as zone_locations,cities.name as city_name
 
 FROM `teachers`

LEFT JOIN teacher_bands tb on tb.id =teachers.teacher_band_id
LEFT JOIN teacher_qualifications tq on tq.teacher_id = teachers.id
LEFT JOIN teacher_subject_preferences tp on tp.teacher_id = teachers.id and subject_p!=''
LEFT JOIN cities on cities.id = teachers.city

LEFT JOIN(
    
SELECT locations.id,tlp.teacher_id,concat(zones.name,': ',GROUP_CONCAT(DISTINCT(locations)) ) as zlocations
    
FROM locations
    
LEFT JOIN teacher_location_preferences tlp ON tlp.location_id = locations.id  
LEFT JOIN zones on zones.id = locations.zone_id
    
GROUP By tlp.teacher_id,zones.name
    
)location_pref ON location_pref.teacher_id = teachers.id

LEFT JOIN teacher_labels tl on tl.teacher_id = teachers.id and label_p!='' 
LEFT JOIN teacher_tuition_categories ttc on ttc.teacher_id = teachers.id and category_p!=''
LEFT JOIN teacher_institute_preferences tip on tip.teacher_id = teachers.id and institute_p!=''

LEFT JOIN ITEMS I ON I.ID = location_pref.teacher_id and locs_p!=''
LEFT JOIN Subj S ON S.ID = tp.class_subject_mapping_id and subject_p!=''
LEFT JOIN LABELS L ON L.ID = tl.label_id and label_p!='' 
LEFT JOIN CATEGORY C ON C.ID = ttc.tuition_category_id and category_p!=''
LEFT JOIN TQUAL  ON TQUAL.ID = teachers.id and qualification_p !=''
LEFT JOIN INST  ON INST.ID = tip.institute_id and institute_p!=''

WHERE (first_name='' OR teachers.fullname LIKE CONCAT('%',first_name,'%'))
AND (email_p='' OR teachers.email LIKE CONCAT('%',email_p,'%'))
AND (mobile_number='' OR teachers.mobile1 LIKE CONCAT('%',mobile_number,'%'))
AND (cnic_number_p='' OR teachers.cnic_number LIKE CONCAT('%',cnic_number_p,'%'))
AND (locs_p='' OR I.ID=location_pref.teacher_id)
AND (subject_p='' OR S.ID = tp.class_subject_mapping_id)
AND (label_p='' OR tl.label_id = L.ID)
AND (category_p = '' OR ttc.tuition_category_id = C.ID)
AND (teacher_band=0 OR teachers.teacher_band_id = teacher_band)
AND (gender=0 OR teachers.gender_id = gender)
AND (age_p=0 OR (DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365)>=age_p)

AND (exp_p=''OR 
			 CASE WHEN exp_p='0.5' THEN 
			 teachers.experience = '0.5' END
			 OR
			 CASE WHEN exp_p='1' THEN 
			 teachers.experience>=1 END
     		 OR
     		 CASE WHEN exp_p='5' THEN 
			 teachers.experience>=5 END
     		 OR
     		 CASE WHEN exp_p='10' THEN 
			 teachers.experience>=10 END
     		 OR
     		 CASE WHEN exp_p='15' THEN 
			 teachers.experience>=15 END
			 OR		
			 CASE WHEN exp_p<'0.5' THEN 
			 teachers.experience<'0.5' END )



AND (is_active_p=0 OR teachers.is_active=is_active_p)
AND (is_approved_p=0 OR teachers.is_approved=is_approved_p)
AND (marital_status=0 OR teachers.marital_status_id = marital_status)
AND (reg_number_p='' OR teachers.registeration_no LIKE CONCAT('%',reg_number_p,'%'))
AND (qualification_p='' OR teachers.id = TQUAL.ID)
AND (institute_p = '' OR tip.institute_id = INST.ID)
AND (minimum_fee = 0 OR teachers.expected_minimum_fee = minimum_fee)
AND ( timing_p = '' OR 
		CASE WHEN timing_p='anytime' THEN 
		teachers.suitable_timings='morning' OR 	    teachers.suitable_timings='evening' END
		OR
		CASE WHEN timing_p!='anytime' THEN teachers.suitable_timings=timing_p END )

GROUP BY teachers.id
ORDER BY teachers.teacher_band_id,teachers.id ASC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `load_tuitions` (IN `tuition_code_p` VARCHAR(100), IN `tuition_date_p` DATE, IN `assign_status_p` INT(11), IN `is_active_p` INT(11), IN `is_approved_p` INT(11), IN `class_p` INT(11), IN `subject_p` INT(11), IN `tuition_start_date` DATE, IN `tuition_end_date` DATE, IN `label_p` VARCHAR(100), IN `categories_p` VARCHAR(100), IN `location_p` VARCHAR(100), IN `contact_no_p` VARCHAR(100), IN `contact_person_p` VARCHAR(100), IN `gender_p` INT(11), IN `timing_p` VARCHAR(100), IN `fee_p` INT(11))  NO SQL
    DETERMINISTIC
BEGIN   

SET @p1=',';
CALL `SplitStringLabels`(label_p, @p1);

SET @p2=',';
CALL `SplitString`(categories_p, @p2);

SET @p3=',';
CALL `SplitStringDL`(location_p, @p3);

SELECT tuitions.`id`, `student_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_status_id`,
`tuition_code`, `no_of_students`, location.`location_id`, `tuition_date` ,  GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br>') as subjects,ci.subject_id,
ts.name as tuition_status,ts.color,ci.cid,contact_person,contact_no,contact_no2,
location.location_name,ci.sid
 FROM `tuitions`

LEFT join tution_status ts  on ts.id= tuitions.tuition_status_id

LEFT JOIN (

SELECT concat(c.name,': ',GROUP_CONCAT(s.name separator ',')) as class_subjects, 
c.id as cid, s.id as sid, td.tuition_id as tid ,
GROUP_CONCAT(s.id separator ',') as subject_id
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
LEFT JOIN classes c on c.id = csm.class_id
LEFT JOIN subjects s on s.id = csm.subject_id

GROUP BY c.id, td.tuition_id

)ci ON tuitions.id = ci.tid

LEFT JOIN  tuition_labels tl on tl.tuition_id = tuitions.id

LEFT JOIN LABELS L ON L.ID = tl.label_id
LEFT JOIN ITEMS I ON I.ID = tuitions.tuition_catefory_id

LEFT JOIN (
 
 SELECT locations.id as locId, locations as location_name,  tuitions.location_id, D.ID as locationId FROM locations
 LEFT JOIN tuitions on tuitions.location_id =  locations.id
 LEFT JOIN Degree D ON D.ID = locations.id

)location ON location.location_id = tuitions.location_id


WHERE 1

AND (tuition_code_p='' OR tuitions.tuition_code LIKE CONCAT('%',tuition_code_p,'%'))
AND (tuition_start_date='' OR tuitions.tuition_date >= tuition_start_date) 
AND (tuition_end_date='' OR tuitions.tuition_date <= tuition_end_date)
AND (assign_status_p=0 OR tuitions.tuition_status_id = assign_status_p)

AND (is_active_p=0 OR tuitions.is_active = is_active_p)
AND (gender_p=0 OR tuitions.teacher_gender = gender_p)
AND (timing_p='' OR tuitions.suitable_timings = timing_p)
AND (fee_p=0 OR fee_p BETWEEN tuitions.tuition_fee AND tuitions.tuition_max_fee )
AND (is_approved_p = 0 OR tuitions.is_approved = is_approved_p)
AND (class_p =0 OR ci.cid = class_p)
AND (subject_p =0 OR FIND_IN_SET(subject_p, ci.subject_id))
AND (label_p='' OR tl.label_id = L.ID)
AND (categories_p='' OR tuitions.tuition_catefory_id = I.ID)
AND (location_p='' OR tuitions.location_id = location.locationId)
AND (contact_no_p='' OR tuitions.contact_no LIKE CONCAT('%',contact_no_p,'%'))
AND (contact_person_p='' OR tuitions.contact_person LIKE CONCAT('%',contact_person_p,'%'))

GROUP by tuitions.id;
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `matched_teachers` (IN `id_p` INT(11), IN `teacher_band_p` INT(11), IN `label_p` INT(11), IN `dg_level_p` VARCHAR(100), IN `age_p` INT(11), IN `exp_p` FLOAT(11), IN `gender_p` INT(11), IN `subject_pref_p` INT(11), IN `location_p` INT(11), IN `fee_p` INT(11), IN `suitable_timings_p` INT(11), IN `institute_p` INT(11), IN `category_p` INT(11))  NO SQL
BEGIN
declare LId INTEGER;
declare t_gender INTEGER;
declare t_band_id INTEGER;
declare t_experience INTEGER;
declare t_tuition_fee INTEGER;
declare t_suitable_timings INTEGER;
declare t_teacher_age INTEGER;

SET @p2=',';
CALL `SplitStringDL`(dg_level_p, @p2);

SELECT `location_id` INTO LId FROM `tuitions` WHERE id= id_p;
SELECT `teacher_gender` INTO t_gender FROM `tuitions` WHERE id= id_p;
SELECT `band_id` INTO t_band_id FROM `tuitions` WHERE id= id_p;
SELECT `experience` INTO t_experience FROM `tuitions` WHERE id= id_p;
SELECT `tuition_fee` INTO t_tuition_fee FROM `tuitions` WHERE id= id_p;
SELECT `suitable_timings` INTO t_suitable_timings FROM `tuitions` WHERE id= id_p;
SELECT `teacher_age` INTO t_teacher_age FROM `tuitions` WHERE id= id_p;

SELECT T.id as teacher_id,T.firstname,T.lastname,T.city, T.teacher_band_id,
T.mobile1,T.email, lp.location_id,id_p as id, td.id as td_id,T.experience,
lbl.name as labels,
DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365 as agey,
CASE WHEN tbm.id IS NULL THEN 0 ELSE 1 END as tbm_id, tb.name as band_name,
T.teacher_photo

FROM teachers T

INNER JOIN tuition_details td on td.tuition_id = id_p
INNER JOIN class_subject_mappings csm on csm.id =  td.class_subject_mapping_id

INNER JOIN teacher_subject_preferences tp on 
(subject_pref_p = 0 OR tp.teacher_id = T.id)
and tp.class_subject_mapping_id = td.class_subject_mapping_id 

INNER JOIN  teacher_tuition_categories tc on 
(category_p = 0 OR T.id = tc.teacher_id and tc.tuition_category_id = category_p)

INNER JOIN teacher_bands tb on 
(teacher_band_p = 0 OR tb.id = T.teacher_band_id and T.teacher_band_id = t_band_id )


INNER JOIN teacher_location_preferences lp on 
(location_p = 0 OR (lp.teacher_id = T.id and lp.location_id = LId ))

INNER JOIN teacher_institute_preferences teacher_ip on (institute_p = 0 OR teacher_ip.teacher_id = T.id)
INNER JOIN tuition_institute_preferences tuition_ip  on (institute_p = 0 OR (tuition_ip.institute_id =  teacher_ip.institute_id and tuition_ip.tuition_id = id_p) )

INNER JOIN teacher_labels tl on (label_p = 0 OR tl.teacher_id = T.id)
INNER JOIN tuition_labels tlabel  on (label_p=0 OR (tlabel.label_id =  tl.label_id and tlabel.tuition_id = id_p) )

LEFT JOIN teacher_bookmarks tbm on tbm.tuition_id = id_p AND tbm.teacher_id = T.id

INNER JOIN subjects s on s.id = csm.subject_id
INNER JOIN classes c on c.id = csm.class_id
INNER JOIN tlabels lbl on (label_p =0 OR lbl.id = tl.label_id)


WHERE 
(gender_p = 0 OR T.gender_id = t_gender)
and (exp_p = 0 OR T.experience = t_experience) 
and (fee_p = 0 OR T.expected_minimum_fee = t_tuition_fee)
and (suitable_timings_p = 0 OR T.suitable_timings = t_suitable_timings)
and (age_p = 0 OR T.age = t_teacher_age)


GROUP BY T.id
ORDER BY tb.display_order, T.id ASC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `matched_tuitions` (IN `location_p` VARCHAR(100), IN `category_id_p` INT(11), IN `subject_p` VARCHAR(100), IN `class_p` VARCHAR(100))  NO SQL
BEGIN

SELECT tuitions.*,tc.name as c_name,GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br>') as subjects,locations.id as location_id ,locations,
ci.class_name,GROUP_CONCAT(DISTINCT(ci.class_names) separator ',') as classes
From tuitions

LEFT JOIN (

SELECT concat(c.name,':',GROUP_CONCAT(s.name separator ',')) as class_subjects, 
c.id as cid, s.id as sid, td.tuition_id as tid ,c.name as class_name,
GROUP_CONCAT(s.id separator ',') as subject_id,GROUP_CONCAT(c.name separator ',') as class_names
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
LEFT JOIN classes c on c.id = csm.class_id
LEFT JOIN subjects s on s.id = csm.subject_id


GROUP BY c.id, td.tuition_id

)ci ON tuitions.id = ci.tid

LEFT JOIN tuition_categories tc on tc.id=tuitions.tuition_catefory_id
LEFT JOIN locations  on locations.id=tuitions.location_id

WHERE tuitions.is_active=1 AND tuitions.is_approved=1
AND (location_p='' OR CONCAT(locations) LIKE CONCAT('%',location_p,'%'))
AND (category_id_p='' OR tuitions.tuition_catefory_id = category_id_p)
AND (subject_p='' OR CONCAT(ci.class_subjects) LIKE CONCAT('%',subject_p,'%') )
AND (class_p='' OR CONCAT(ci.class_names) LIKE CONCAT('%',class_p,'%') )

GROUP BY tuitions.id
ORDER BY tuitions.tuition_code ASC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `matched_tuitions_by_class_subj` (IN `subject_name_p` VARCHAR(100), IN `class_name_p` VARCHAR(100))  NO SQL
BEGIN
SELECT s.id as subject_id, loc.locations, s.name as subjects, 
t.*,c.id as class_id,c.name as class_name FROM `subjects` s 

INNER JOIN  class_subject_mappings csm on csm.subject_id = s.id
INNER JOIN classes c on csm.class_id = c.id
INNER JOIN tuition_details td on td.class_subject_mapping_id=csm.id
INNER JOIN tuitions t on t.id=td.tuition_id
INNER JOIN locations loc on loc.id = t.location_id


WHERE t.is_active=1 AND t.is_approved=1
AND (subject_name_p='' OR CONCAT(s.name) LIKE CONCAT('%',subject_name_p,'%')) 
AND (class_name_p='' OR CONCAT(c.name) LIKE CONCAT('%',class_name_p,'%'))

GROUP BY t.tuition_code
ORDER BY t.tuition_code ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `save_teacher` (IN `user_id_p` INT(11), IN `teacher_band_id` INT(11), IN `marital_status_id` INT(11), IN `gender_id` INT(11), IN `firstname` VARCHAR(100), IN `lastname` VARCHAR(100), IN `registeration_no` VARCHAR(100), IN `father_name` VARCHAR(100), IN `expected_minimum_fee` INT(11), IN `religion` VARCHAR(20), IN `strength` TEXT, IN `no_of_children` INT(11), IN `cnic_number` VARCHAR(100), IN `cnic_front_image` VARCHAR(45), IN `cnic_back_image` VARCHAR(45), IN `email_p` VARCHAR(100), IN `password_p` VARCHAR(100), IN `dob` DATE, IN `landline` VARCHAR(20), IN `mobile1` VARCHAR(20), IN `mobile2` VARCHAR(20), IN `address_line1` VARCHAR(255), IN `address_line2` VARCHAR(255), IN `city` VARCHAR(100), IN `province` VARCHAR(100), IN `zip_code` VARCHAR(20), IN `country` VARCHAR(20), IN `other_detail` TEXT, IN `created_by` VARCHAR(100), IN `created_at` TIMESTAMP, IN `updated_by` VARCHAR(100), IN `updated_at` TIMESTAMP, IN `teacher_id_p` INT(11), IN `photo` VARCHAR(100))  NO SQL
BEGIN

declare tid INTEGER;
declare gid INTEGER;
SELECT id INTO tid
 FROM teachers
 WHERE teachers.user_id =user_id_p;

IF tid IS NULL THEN

INSERT INTO `teachers`(`id`, `user_id`, `teacher_band_id`, `marital_status_id`, `gender_id`, `firstname`, `lastname`, `registeration_no`, `father_name`, `expected_minimum_fee`, `religion`, `strength`, `no_of_children`, `cnic_number`, `cnic_front_image`, `cnic_back_image`, `email`, `password`, `dob`, `landline`, `mobile1`, `mobile2`, `address_line1`, `address_line2`, `city`, `province`, `zip_code`, `country`, `other_detail`, `created_by`, `created_at`, `updated_by`, `updated_at`, `teacher_photo`) 

VALUES (
    teacher_id_p,
  	user_id_p,
 	teacher_band_id,
    marital_status_id,
    gender_id,
    firstname,
    lastname,
    registeration_no,
    father_name,
    expected_minimum_fee,
    religion,
    strength,
    no_of_children,
    cnic_number,
    cnic_front_image,
    cnic_back_image,
    email_p,
    password_p,
    dob,
    landline,
    mobile1,
    mobile2,
    address_line1,
    address_line2,
    city,
    province,
    zip_code,
    country,
    other_detail,
    created_by,
    created_at,
    updated_by,
    updated_at,
    photo
    
    
);

SELECT id FROM teachers WHERE id = (SELECT MAX(ID) FROM teachers);


ELSE

UPDATE `teachers` SET 

`user_id`=user_id_p,
`teacher_band_id`=teacher_band_id,
`marital_status_id`=marital_status_id,
`gender_id`=gender_id,
`gender_id`=gender_id,
`firstname`= firstname,
`lastname`= lastname,
`registeration_no`= registeration_no,
`father_name`= father_name,
`expected_minimum_fee`= expected_minimum_fee,
`religion`= religion,
`strength`= strength,
`no_of_children`= no_of_children,
`cnic_number`= cnic_number,
`cnic_front_image`= cnic_front_image,
`cnic_back_image`= cnic_back_image,
`email`= email_p,
`password`= password,
`dob`= dob,
`landline`= landline,
`mobile1`= mobile1,
`mobile2`= mobile2,
`address_line1`= address_line1,
`address_line2`= address_line2,
`city`= city,
`province`= province,
`zip_code`= zip_code,
`country`= country,
`other_detail`= other_detail,
`created_by`= created_by,
`created_at`= created_at,
`updated_by`= updated_by,
`updated_at`= updated_at,
`teacher_photo` = photo


WHERE `id` = teacher_id_p;

END IF;



END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SplitString` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1))  BEGIN
DECLARE Item           VARCHAR(100) ;
DECLARE ItemList       VARCHAR(100) ;
DECLARE DelimIndex     INT  ;

DROP TEMPORARY TABLE IF EXISTS ITEMS;

 CREATE TEMPORARY TABLE ITEMS (
     ID INT NULL
   ) ENGINE=MEMORY ;
   
 
      SET ItemList = InputString;
      SET DelimIndex = INSTR(ItemList, pDelimiter);
      
      WHILE DelimIndex > 0
		DO
            INSERT INTO ITEMS (ID) VALUES (SUBSTRING(ItemList, 1, DelimIndex - 1));

                        SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);
            SET DelimIndex = INSTR(ItemList, pDelimiter);
		END WHILE; 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SplitStringDL` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1))  NO SQL
BEGIN
DECLARE Item           VARCHAR(100) ;
DECLARE ItemList       VARCHAR(100) ;
DECLARE DelimIndex     INT  ;

DROP TEMPORARY TABLE IF EXISTS Degree;

 CREATE TEMPORARY TABLE Degree (
     ID INT NULL
   ) ENGINE=MEMORY ;
   
 
      SET ItemList = InputString;
      SET DelimIndex = INSTR(ItemList, pDelimiter);

      
      WHILE DelimIndex > 0
		DO
            INSERT INTO Degree (ID) VALUES (SUBSTRING(ItemList, 1, DelimIndex - 1));

                        SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);
            SET DelimIndex = INSTR(ItemList, pDelimiter);
		END WHILE; 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SplitStringLabels` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1))  NO SQL
BEGIN
DECLARE Item           VARCHAR(100) ;
DECLARE ItemList       VARCHAR(100) ;
DECLARE DelimIndex     INT  ;

DROP TEMPORARY TABLE IF EXISTS LABELS;

 CREATE TEMPORARY TABLE LABELS (
     ID INT NULL
   ) ENGINE=MEMORY ;
   
 
      SET ItemList = InputString;
      SET DelimIndex = INSTR(ItemList, pDelimiter);
      
      WHILE DelimIndex > 0
		DO
            INSERT INTO LABELS (ID) VALUES (SUBSTRING(ItemList, 1, DelimIndex - 1));

                        SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);
            SET DelimIndex = INSTR(ItemList, pDelimiter);
		END WHILE; 


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SplitStringS` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1))  NO SQL
BEGIN
DECLARE Item           VARCHAR(100) ;
DECLARE ItemList       VARCHAR(100) ;
DECLARE DelimIndex     INT  ;

DROP TEMPORARY TABLE IF EXISTS Subj;

 CREATE TEMPORARY TABLE Subj (
     ID INT NULL
   ) ENGINE=MEMORY ;
   
 
      SET ItemList = InputString;
      SET DelimIndex = INSTR(ItemList, pDelimiter);
      
      WHILE DelimIndex > 0
		DO
            INSERT INTO Subj (ID) VALUES (SUBSTRING(ItemList, 1, DelimIndex - 1));

                        SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);
            SET DelimIndex = INSTR(ItemList, pDelimiter);
		END WHILE; 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_test_dynamic_sql` (IN `locs_p` VARCHAR(100))  BEGIN
SET @p1=',';
CALL `SplitString`(locs_p, @p1);

SELECT teachers.`id`, `user_id`, `teacher_band_id`, `marital_status_id`, `gender_id`, `firstname`, `lastname`, `registeration_no`, `father_name`, `expected_minimum_fee`, `religion`, `strength`, `no_of_children`, `cnic_number`, `cnic_front_image`, `cnic_back_image`, `email`, `password`, `dob`, `landline`, `mobile1`, `mobile2`, `address_line1`, `address_line2`, `city`, `province`, `zip_code`, `country`, `other_detail`FROM `teachers`

LEFT JOIN teacher_qualifications tq on tq.teacher_id = teachers.id
LEFT JOIN teacher_degree_level tdl on tdl.id = tq.teacher_degree_level_id

LEFT JOIN teacher_subject_preferences tp on tp.teacher_id = teachers.id
LEFT JOIN subjects s on s.id = tp.subject_id

LEFT JOIN teacher_location_preferences lp on lp.teacher_id = teachers.id
LEFT JOIN locations loc on loc.id = lp.location_id
JOIN ITEMS I ON I.ID = loc.id


GROUP BY teachers.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TeacherTuitionCategory` (IN `InputString` VARCHAR(100), IN `pDelimiter` CHAR(1))  NO SQL
BEGIN
DECLARE Item           VARCHAR(100) ;
DECLARE ItemList       VARCHAR(100) ;
DECLARE DelimIndex     INT  ;

DROP TEMPORARY TABLE IF EXISTS CATEGORY;

 CREATE TEMPORARY TABLE CATEGORY (
     ID INT NULL     
   ) ENGINE=MEMORY ;
   
 
      SET ItemList = InputString;
      SET DelimIndex = INSTR(ItemList, pDelimiter);
      
      WHILE DelimIndex > 0
		DO
            INSERT INTO CATEGORY (ID) VALUES (SUBSTRING(ItemList, 1, DelimIndex - 1));

                        SET ItemList = SUBSTRING(ItemList, DelimIndex+1, 100-DelimIndex);
            SET DelimIndex = INSTR(ItemList, pDelimiter);
            
		END WHILE; 
        
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_applications` (IN `tuition_id_p` INT(11))  NO SQL
SELECT tapp.id,tapp.created_at,teachers.band_name,teachers.fullname,teachers.email,tapp.teacher_id,
teachers.experience,teachers.teacher_photo,teachers.mobile1,teachers.labels,
DATEDIFF(CURRENT_DATE, STR_TO_DATE(teachers.dob, '%Y-%m-%d'))/365 as agey,astatus.name as status_name

FROM `teacher_applications` tapp

LEFT join(
    
	SELECT teachers.id,teachers.fullname,teachers.experience, 	teachers.teacher_photo,
    teachers.email,teachers.mobile1,tb.name as band_name,teachers.dob,
    GROUP_CONCAT(labels.name SEPARATOR ',') as  labels    
    FROM teachers
    
    LEFT JOIN teacher_bands tb on tb.id = teachers.teacher_band_id
    LEFT JOIN teacher_labels tl on tl.teacher_id = teachers.id
    LEFT JOIN labels on labels.id = tl.label_id
    GROUP BY teachers.id

)teachers on teachers.id = tapp.teacher_id
LEFT JOIN application_status astatus ON astatus.id =  tapp.application_status_id

WHERE tapp.`tuition_id` = tuition_id_p
GROUP BY tapp.id
ORDER BY  teachers.id ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_bookmark` (IN `tuition_id_p` INT(11))  NO SQL
BEGIN
		SELECT tbm.`id`, tbm.`tuition_id`, tbm.`teacher_id`, t.firstname, t.lastname ,t.fullname, t.email,t.teacher_photo, t.mobile1,
		td.id as td_id,tb.name as band_name,t.experience, DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365 as agey,
		GROUP_CONCAT(CONCAT_WS('-', classes.name, subjects.name) SEPARATOR '-') as subjects,lbl.name as label_name

		FROM `teacher_bookmarks` tbm
		INNER JOIN teachers t on t.id = tbm.teacher_id
		INNER JOIN teacher_labels tl on tl.teacher_id = t.id
		LEFT JOIN tlabels lbl on lbl.id = tl.label_id

		INNER JOIN tuition_details td on td.tuition_id = tbm.tuition_id 
		INNER JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
		INNER JOIN subjects on subjects.id = csm.subject_id
		INNER JOIN classes on classes.id = csm.class_id

		LEFT JOIN teacher_bands tb on tb.id = t.teacher_band_id

		WHERE tbm.tuition_id = tuition_id_p
		GROUP by t.id
		ORDER BY tb.display_order ASC,t.experience DESC, agey ASC;


	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_experiences` (IN `teacher_id` INT(11))  NO SQL
SELECT `id`, `teacher_id`, `experience_document`  , `experience` 
FROM `teacher_experiences`
 WHERE teacher_experiences.teacher_id=teacher_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_grades_categories` (IN `teacher_id_p` INT(11))  NO SQL
BEGIN
SELECT tuition_categories.name, tc.id,tc.teacher_id,tc.tuition_category_id FROM `teacher_tuition_categories` tc 
INNER JOIN tuition_categories  on tuition_categories.id=tc.tuition_category_id 
WHERE tc.teacher_id =teacher_id_p;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_institutes` (IN `teacher_id_p` INT(11))  NO SQL
BEGIN
SELECT institutes.name, tip.id,tip.teacher_id,tip.institute_id FROM `teacher_institute_preferences` tip 
INNER JOIN institutes  on institutes.id=tip.institute_id 
WHERE tip.teacher_id =teacher_id_p;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_labels` (IN `teacher_id_p` INT(11))  NO SQL
BEGIN
SELECT l.name,tl.id,tl.teacher_id FROM `teacher_labels` tl 
INNER JOIN teachers t on t.id=tl.teacher_id 
INNER JOIN tlabels l on l.id=tl.label_id 
WHERE t.id =teacher_id_p;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_locations` (IN `teacher_id` INT(11))  NO SQL
SELECT  lp.id  , lp.`teacher_id`, lp.`created_at`, lp.`updated_at`,loc.zoneid,
loc.zone_id,concat(loc.zone_name,': ',GROUP_CONCAT(DISTINCT(loc.zlocations) separator ',')) as zone_locations

FROM `teacher_location_preferences` lp
inner join (

select locations.id,locations,zone_id,zones.id as zoneid,
zones.name as zone_name,GROUP_CONCAT(DISTINCT(locations)) as zlocations,
GROUP_CONCAT(DISTINCT(locations.id) ) as locationids
    
from locations
    
inner join zones on zones.id = locations.zone_id
GROUP By locations.id
    
)loc on loc.id = lp.location_id


WHERE lp.teacher_id=teacher_id
GROUP By loc.zone_id

ORDER BY loc.zone_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_qualifications` (IN `teacher_id` INT(11))  NO SQL
SELECT tq.id as qid, tq.`teacher_id` as teacherid, `highest_degree`, 
`passing_year`, `institution`, `grade`, `degree_document`,tq.qualification_name
FROM `teacher_qualifications` tq
WHERE tq.teacher_id =teacher_id
ORDER BY tq.highest_degree ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_references` (IN `teacher_id` INT(11))  NO SQL
SELECT `id`, `teacher_id`, `name`, `contact_no`, `cnic_no`, `address`, `relationship` 
FROM `teacher_references` 
WHERE teacher_references.teacher_id=teacher_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_subject_preferences` (IN `teacher_id` INT(11))  NO SQL
BEGIN
SELECT tp.id as tpid, tp.teacher_id, tp.class_subject_mapping_id,ci.cid,ci.name as class_name,
concat(ci.name,': ',GROUP_CONCAT(DISTINCT(ci.grade_subjects) separator ',')) as subjects
FROM teacher_subject_preferences tp

INNER JOIN (

	SELECT GROUP_CONCAT(DISTINCT(s.name) separator ',') as grade_subjects, 
	csm.id as csmid,c.id as cid,c.name
	FROM class_subject_mappings csm 
	
	INNER JOIN classes c on c.id = csm.class_id
	INNER JOIN subjects s on s.id = csm.subject_id
	
	GROUP BY csm.id

)ci on ci.csmid = tp.class_subject_mapping_id


WHERE tp.teacher_id = teacher_id
GROUP BY cid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `teacher_tuitionhistory` (IN `teacher_id_p` INT(11))  NO SQL
SELECT th.`id`, th.`teacher_id`, th.`tuition_detail_id`, th.`assign_date`, th.`feedback_rating`, th.`feedback_comment`,
 th.`tuition_fee`, th.`tuition_start_date`, th.`tuition_end_date`, td.is_trial as tuition_status,t.tuition_code
 FROM `tuition_history` th 
 
 INNER JOIN tuition_details td on td.id=th.tuition_detail_id
 INNER JOIN tuitions t on t.id=td.tuition_id
  
 WHERE th.`teacher_id` = teacher_id_p$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TestSpliString` ()  NO SQL
BEGIN
Declare ParamList varchar(254);
DECLARE Work varchar(254);
DECLARE Value varchar(100);
DECLARE Ptr int;

SELECT ParamList = '7197, 8285, 8376, 8377, 8378, 8379, 8380, 8381, 8382, 8383, 8384, 8385, 8411';

create table test (SiteID int);
SELECT Ptr = charindex(',', ParamList);

WHILE Ptr > 0
	DO
    	SELECT Work = Substring(ParamList, 1, Ptr - 1);
        
        INSERT INTO test (SiteID) SELECT Cast(Work as int);
    	SET ParamList = Substring(ParamList, Ptr + 1, 1000);
    	SET Ptr = charindex(',', Paramlist);
        
        
    END WHILE;
    
    IF Len(ParamList) > 0
    THEN    
        INSERT  INTO test (SiteID) SELECT Cast(paramList as int);
    END IF;
    
    SELECT * from test;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tuitions_applied` (IN `teacher_id_p` INT(11))  NO SQL
SELECT tapp.id as application_id,tapp.tuition_id,tapp.notes,tapp.teacher_id,tapp.created_at, tu.tuition_code,
GROUP_CONCAT(DISTINCT tlabels.name SEPARATOR ',') as labels,tb.name as band_name,tu.special_notes,
GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '<br>') as subject_name

from teacher_applications tapp

INNER JOIN teachers t on t.id = tapp.`teacher_id`
INNER JOIN tuitions tu on tu.id = tapp.`tuition_id`


INNER JOIN (

SELECT concat(c.name,': ',GROUP_CONCAT(s.name separator ',')) as class_subjects, 
c.id, td.tuition_id as tid 
FROM tuition_details td 

INNER JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
INNER JOIN classes c on c.id = csm.class_id
INNER JOIN subjects s on s.id = csm.subject_id

GROUP BY c.id, td.tuition_id

)ci on tu.id = ci.tid

INNER JOIN teacher_bands tb on tb.id = t.teacher_band_id

LEFT JOIN tuition_labels tul on tul.tuition_id = tu.id 
LEFT JOIN tlabels on tlabels.id = tul.label_id


Where tapp.teacher_id = teacher_id_p

Group by tapp.tuition_id
ORDER BY tb.display_order, tapp.id ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tuition_details` (IN `tuition_id_p` INT(11))  NO SQL
SELECT td.`id`, td.`tuition_id`, td.`class_subject_mapping_id`, td.`teacher_id`, td.`assign_date` , td.is_trial,
t.firstname, t.lastname, t.email, t.mobile1, t.teacher_photo,t.fullname, c.name as class_name,s.name as subject_name, th.id as tuiion_history_id

FROM `tuition_details` td

LEFT JOIN teachers t on t.id = td.teacher_id
INNER JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id
INNER JOIN classes c on c.id=csm.class_id
INNER JOIN subjects s on s.id= csm.subject_id

LEFT JOIN tuition_history th on th.teacher_id = td.teacher_id AND th.tuition_detail_id = td.id

WHERE td.tuition_id=tuition_id_p$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tuition_labels` (IN `tuition_id_p` INT(11))  NO SQL
SELECT l.name,tl.id FROM `tuition_labels` tl INNER JOIN tuitions t on t.id=tl.tuition_id INNER JOIN labels l on l.id=tl.label_id WHERE t.id =tuition_id_p$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tuition_short_view` (IN `tuition_id_p` INT(11))  NO SQL
SELECT tuitions.id as t_id,contact_person,contact_no,contact_no2,address,GROUP_CONCAT(DISTINCT(labels.names) separator ',') as lebel_names,
special_notes,GROUP_CONCAT(DISTINCT(ci.class_subjects) separator '\r\n') as subject, institute.institute_name,
locations.locations as location_name, gender.name as gender,suitable_timings,special_notes,
tuition_fee,tuition_max_fee,tb.name as band_name,teaching_duration
from tuitions 

LEFT JOIN (

SELECT CONCAT(c.name,': ',GROUP_CONCAT(s.name separator ',')) AS class_subjects, 
c.id, td.tuition_id as tid 
FROM tuition_details td 

LEFT JOIN class_subject_mappings csm ON csm.id = td.class_subject_mapping_id
LEFT JOIN classes c ON c.id = csm.class_id
LEFT JOIN subjects s ON s.id = csm.subject_id

GROUP BY c.id, td.tuition_id

)ci on tuitions.id = ci.tid

LEFT JOIN (

SELECT  inst.name as institute_name,tip.tuition_id  FROM institutes inst
LEFT JOIN tuition_institute_preferences tip ON tip.institute_id = inst.id
GROUP BY tip.tuition_id

)institute ON institute.tuition_id = tuitions.id

LEFT JOIN(

 SELECT lbl.tuition_id,lbl.id ,tlabels.name as names FROM tuition_labels lbl
 LEFT JOIN tlabels  ON lbl.label_id =  tlabels.id
 
)labels on labels.tuition_id = tuitions.id

LEFT JOIN teacher_bands tb ON tb.id = tuitions.band_id
LEFT JOIN locations ON locations.id  = tuitions.location_id
LEFT JOIN gender ON gender.id  = tuitions.teacher_gender


WHERE tuitions.id = tuition_id_p

GROUP BY tuitions.id
ORDER BY tb.name ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_matched` (IN `id_p` INT(11))  NO SQL
    DETERMINISTIC
SELECT tuitions.`id`, `student_id`, `tuition_catefory_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_assignment_status_id`, 
`tuition_code`, `no_of_students`, tuitions.`location_id`, `tuition_date`, `special_notes` ,teachers.id as teacher_id,
teachers.firstname,teachers.lastname,teachers.city, teachers.teacher_band_id,teachers.mobile1,subjects.name as subject_name,classes.name as class_name,teachers.email


FROM `tuitions` 

INNER JOIN teacher_location_preferences lp on lp.location_id = tuitions.location_id
INNER JOIN teachers on teachers.id = lp.teacher_id

INNER JOIN tuition_details td on td.tuition_id = tuitions.id
INNER JOIN class_subject_mappings csm on csm.id =  td.class_subject_mapping_id

INNER JOIN teacher_subject_preferences tp on tp.teacher_id = teachers.id
INNER JOIN subjects on subjects.id = tp.subject_id
INNER JOIN classes on classes.id = tp.class_id

WHERE tuitions.id = id_p
GROUP BY teacher_id
ORDER By teachers.teacher_band_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `application_status`
--

CREATE TABLE `application_status` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sms_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `application_status`
--

INSERT INTO `application_status` (`id`, `name`, `description`, `sms_description`, `created_at`, `updated_at`) VALUES
(1, 'Pending', 'Pending Description', 'Pending SMS Description', '2017-05-25 07:03:01', '2017-05-25 07:03:01'),
(2, 'Selected', 'Selected Description', 'Selected SMS Description', '2017-05-25 07:03:43', '2017-05-25 07:03:43'),
(3, 'Shortlisted ', 'Shortlisted Description', 'Shortlisted SMS Description', '2017-05-25 07:04:21', '2017-05-25 07:04:21'),
(4, 'Closed', 'Closed Description', 'Closed SMS Description', '2017-05-25 07:04:35', '2017-05-25 07:04:35');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `province_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `province_id`, `created_at`, `updated_at`) VALUES
(1, 'Ahmadpur East', 1, '2017-04-20 09:54:23', '0000-00-00 00:00:00'),
(2, 'Ahmed Nager Chatha', 1, '2017-04-20 09:55:17', '0000-00-00 00:00:00'),
(3, 'Ali Khan Abad', 1, '2017-04-20 09:58:37', '0000-00-00 00:00:00'),
(4, 'Alipur', 1, '2017-04-20 09:58:37', '0000-00-00 00:00:00'),
(5, 'Arifwala', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(6, 'Bhera', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(7, 'Bhalwal', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(8, 'Bahawalnagar', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(9, 'Bahawalpur', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(10, 'Bhakkar', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(11, 'Burewala', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(12, 'Chillianwala', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(13, 'Chakwal', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(14, 'Chak Jhumra', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(15, 'Chichawatni', 1, '2017-04-20 09:58:38', '0000-00-00 00:00:00'),
(16, 'Chishtian', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(17, 'Dajkot', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(18, 'Daska', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(19, 'Davispur', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(20, 'Darya Khan', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(21, 'Dera Ghazi Khan', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(22, 'Dhaular', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(23, 'Dina', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(24, 'Dinga', 1, '2017-04-20 10:00:38', '0000-00-00 00:00:00'),
(25, 'Dipalpur', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(26, 'Faisalabad', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(27, 'Fateh Jang', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(28, 'Ghakhar Mandi', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(29, 'Gojra', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(30, 'Gujranwala', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(31, 'Gujrat', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(32, 'Gujar Khan', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(33, 'Harappa', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(34, 'Hafizabad', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(35, 'Haroonabad', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(36, 'Hasilpur', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(37, 'Haveli Lakha', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(38, 'Jalalpur Jattan', 1, '2017-04-20 10:06:40', '0000-00-00 00:00:00'),
(39, 'Jampur', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(40, 'Jaranwala', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(41, 'Jhang', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(42, 'Jhelum', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(43, 'Kalabagh', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(44, 'Karor Lal Esan', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(45, 'Kamalia', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(46, 'Kamoki', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(47, 'Khanewal', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(48, 'Khanpur', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(49, 'Khanqah Sharif', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(50, 'Kharian', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(51, 'Khushab', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(52, 'Kot Adu', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(53, 'Jauharabad', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(54, 'Lahore', 1, '2017-04-20 10:06:41', '0000-00-00 00:00:00'),
(55, 'Lalamusa', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(56, 'Layyah', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(57, 'Liaquat Pur', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(58, 'Lodhran', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(59, 'Malakwal', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(60, 'Mamoori', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(61, 'Mailsi', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(62, 'Mandi Bahauddin', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(63, 'Mian Channu', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(64, 'Mianwali', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(65, 'Multan', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(66, 'Murree', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(67, 'Muridke', 1, '2017-04-20 10:13:00', '0000-00-00 00:00:00'),
(68, 'Mianwali Bangla', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(69, 'Muzaffargarh', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(70, 'Narowal', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(71, 'Nankana Sahib', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(72, 'Okara', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(73, 'Renala Khurd', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(74, 'Pakpattan', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(75, 'Pattoki', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(76, 'Pind Dadan Khan', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(77, 'Pir Mahal', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(78, 'Qaimpur', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(79, 'Qila Didar Singh', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(80, 'Rabwah', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(81, 'Raiwind', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(82, 'Rajanpur', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(83, 'Rahim Yar Khan', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(84, 'Rawalpindi', 1, '2017-04-20 10:13:01', '0000-00-00 00:00:00'),
(85, 'Safdarabad', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(86, 'Sahiwal', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(87, 'Sambrial', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(88, 'samundri', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(89, 'Sangla Hill', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(90, 'Sarai Alamgir', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(91, 'Sargodha', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(92, 'Shakargarh', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(93, 'Sheikhupura', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(94, 'Sialkot', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(95, 'Sohawa', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(96, 'Soianwala', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(97, 'Siranwali', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(98, 'Tandlianwala', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(99, 'Talagang', 1, '2017-04-20 10:17:14', '0000-00-00 00:00:00'),
(100, 'Taxila', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(101, 'Toba Tek Singh', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(102, 'Vehari', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(103, 'Wah Cantonment', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(104, 'Wazirabad', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(105, 'Yazman', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(106, 'Zafarwal', 1, '2017-04-20 10:17:15', '0000-00-00 00:00:00'),
(107, 'Badin', 2, '2017-04-20 10:18:54', '0000-00-00 00:00:00'),
(108, 'Bhirkan', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(109, 'Bhiria City', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(110, 'Bhiria Road', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(111, 'Rajo Khanani', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(112, 'Chak', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(113, 'Dadu', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(114, 'Digri', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(115, 'Diplo', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(116, 'Dokri', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(117, 'Ghotki', 2, '2017-04-20 10:23:31', '0000-00-00 00:00:00'),
(118, 'Haala', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(119, 'Hyderabad', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(120, 'Islamkot', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(121, 'Jacobabad', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(122, 'Jamshoro', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(123, 'Jungshahi', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(124, 'Kandhkot', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(125, 'Kandiaro', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(126, 'Karachi', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(127, 'Kashmore', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(128, 'Keti Bandar', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(129, 'Khadro', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(130, 'Khairpur', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(131, 'Khipro', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(132, 'Kotri', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(133, 'Larkana', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(134, 'Matiari', 2, '2017-04-20 10:23:32', '0000-00-00 00:00:00'),
(135, 'Mehar', 2, '2017-04-20 10:23:33', '0000-00-00 00:00:00'),
(136, 'Mirpur Khas', 2, '2017-04-20 10:23:33', '0000-00-00 00:00:00'),
(137, 'Mithani', 2, '2017-04-20 10:26:53', '0000-00-00 00:00:00'),
(138, 'Mithi', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(139, 'Mehrabpur', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(140, 'Moro', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(141, 'Nagarparkar', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(142, 'Naudero', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(143, 'Naushahro Feroze', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(144, 'Naushara', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(145, 'Qambar', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(146, 'Qasimabad', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(147, 'Ranipur', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(148, 'Ratodero', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(149, 'Rohri', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(150, 'Sakrand', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(151, 'Sanghar', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(152, 'Shahbandar', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(153, 'Shahdadkot', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(154, 'Shahdadpur', 2, '2017-04-20 10:26:54', '0000-00-00 00:00:00'),
(155, 'Shahpur Chakar', 2, '2017-04-20 10:26:55', '0000-00-00 00:00:00'),
(156, 'Shikarpaur', 2, '2017-04-20 10:26:55', '0000-00-00 00:00:00'),
(157, 'Sinjhoro', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(158, 'Sukkur', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(159, 'Tangwani', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(160, 'Tando Adam Khan', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(161, 'Tando Allahyar', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(162, 'Tando Muhammad Khan', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(163, 'Thatta', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(164, 'Thari Mirwah', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(165, 'Umerkot', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00'),
(166, 'Warah', 2, '2017-04-20 10:28:20', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Primary', '2016-10-26 05:44:22', '2017-05-22 23:48:05'),
(2, 'Secondary', '2016-10-26 05:44:22', '0000-00-00 00:00:00'),
(3, 'Higher Secondary', '2016-10-26 05:44:22', '0000-00-00 00:00:00'),
(4, 'Master', '2016-10-28 10:16:29', '2016-10-29 00:12:49'),
(5, 'Others', '2016-11-24 04:59:26', '2016-11-24 04:59:38'),
(6, 'A Levels', '2016-12-01 18:24:21', '2016-12-01 18:24:21'),
(7, 'O Levels', '2016-12-01 18:24:32', '2016-12-01 18:24:32'),
(8, 'Fsc Pre Eng', '2016-12-01 18:25:14', '2016-12-01 18:25:14'),
(9, 'Fsc Pre Medical', '2017-04-24 08:01:58', '2017-04-24 08:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_mappings`
--

CREATE TABLE `class_subject_mappings` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_subject_mappings`
--

INSERT INTO `class_subject_mappings` (`id`, `class_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2016-10-27 07:49:53', '2016-10-27 07:49:53'),
(2, 1, 1, '2016-10-27 07:50:01', '2016-10-27 07:50:01'),
(4, 2, 2, '2016-10-28 04:51:03', '2016-10-28 04:51:03'),
(5, 2, 1, '2016-10-28 04:51:08', '2016-10-28 04:51:08'),
(6, 2, 3, '2016-10-28 04:51:13', '2016-10-28 04:51:13'),
(7, 3, 2, '2016-10-31 02:48:53', '2016-10-31 02:48:53'),
(8, 3, 1, '2016-10-31 02:48:59', '2016-10-31 02:48:59'),
(9, 3, 3, '2016-10-31 02:49:08', '2016-10-31 02:49:08'),
(10, 3, 15, '2016-10-31 08:02:20', '2016-10-31 08:02:20'),
(11, 4, 2, '2016-11-22 07:35:43', '2016-11-22 07:35:43'),
(12, 4, 1, '2016-11-22 07:35:51', '2016-11-22 07:35:51'),
(13, 4, 3, '2016-11-22 07:35:57', '2016-11-22 07:35:57'),
(16, 5, 18, '2016-11-24 05:13:05', '2016-11-24 05:13:05'),
(17, 5, 16, '2016-11-24 05:17:59', '2016-11-24 05:17:59'),
(18, 8, 1, '2016-12-01 18:27:13', '2016-12-01 18:27:13'),
(19, 8, 15, '2016-12-01 18:27:19', '2016-12-01 18:27:19'),
(20, 9, 1, '2017-04-24 08:03:35', '2017-04-24 08:03:35'),
(21, 9, 3, '2017-04-24 08:03:40', '2017-04-24 08:03:40'),
(22, 9, 15, '2017-04-24 08:03:49', '2017-04-24 08:03:49'),
(23, 1, 3, '2017-05-02 00:23:33', '2017-05-02 00:23:33'),
(30, 1, 17, '2017-05-22 11:15:38', '2017-05-22 11:15:38'),
(31, 1, 18, '2017-05-22 23:51:31', '2017-05-22 23:51:31');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `title`, `subject`, `body`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'Mark Regular', 'Mark Regular', '<p>Dear #fullname#, In nulla sapien, #email#&nbsp;ut luctus ut, mark&nbsp;regular risus. Ut pharetra lacus at tellus cursus pulvinar. Cras placerat tortor a ligula porta, in auctor urna ultricies. Vestibulum ut justo <strong>sapien</strong>. Maecenas ligula leo, suscipit vel fermentum ac, bibendum sed dui. Regards Home Tuition</p>', '2017-01-18 19:00:00', '2017-01-25 01:04:09', 1),
(2, 'Assign Tuition', 'Tuition Assigned', '<p style="margin: 0px 0px 15px; padding: 0px; text-align: justify; font-family: ''Open Sans'', Arial, sans-serif;">Dear #fullname#</p>\r\n<p style="margin: 0px 0px 15px; padding: 0px; text-align: justify; font-family: ''Open Sans'', Arial, sans-serif;">Nam non nisl leo. #email#&nbsp;at mark assigned, eget pretium mauris. Nullam vel diam malesuada, posuere nibh quis, accumsan odio. Morbi dui libero, semper at lorem sed, convallis pulvinar nisl. Maecenas mattis, massa semper vulputate varius, nulla lorem vehicula neque, et aliquet leo urna vel ex. Etiam eleifend felis magna, vitae laoreet turpis iaculis et. Integer quis sodales mauris. Praesent in iaculis odio. Curabitur consequat vitae est at sagittis.</p>\r\n<p>Regards</p>\r\n<p>Home Tuition</p>', '2017-01-19 09:43:55', '2017-05-22 08:23:24', 1),
(3, 'Bulk Email', 'Send email in bulk', '<p><span style="font-family: ''Open Sans'', Arial, sans-serif; text-align: justify;">Dear #fullname#</span></p>\r\n<p><span style="font-family: ''Open Sans'', Arial, sans-serif; text-align: justify;">Lorem ipsum dolor sit #email#, #emailbcc# adipiscing elit. Mauris faucibus suscipit tellus, sit amet rutrum sem accumsan at. Donec quis mauris sed dui tincidunt ornare eu eu orci. Sed in feugiat erat. Aenean condimentum ultrices velit. Nulla facilisi. Vivamus posuere pulvinar vestibulum. Nulla consectetur purus et ipsum pretium, et interdum erat vehicula.</span></p>\r\n<p><span style="font-family: ''Open Sans'', Arial, sans-serif; text-align: justify;">&nbsp;</span><span style="font-family: ''Open Sans'', Arial, sans-serif; text-align: justify;">Regards</span></p>\r\n<p><span style="font-family: ''Open Sans'', Arial, sans-serif; text-align: justify;">Home Tuition</span></p>', '2017-01-19 09:44:22', '2017-01-24 08:09:46', 0),
(4, 'Test', 'Test', '<p>test</p>', '2017-01-19 09:45:09', '2017-01-23 04:54:47', 1),
(5, 'Reset Password', 'Reset Password', '<p>Dear #fullname#, #password# In nulla sapien, #email#&nbsp;ut luctus ut, reset&nbsp;password risus. Ut pharetra lacus at tellus cursus pulvinar. Cras placerat tortor a ligula porta, in auctor urna ultricies. Vestibulum ut justo <strong>sapien</strong>. Maecenas ligula leo, suscipit vel ac, bibendum sed dui. Regards Home Tuition</p>', '2017-01-19 09:45:33', '2017-01-20 00:11:07', 1),
(6, 'Check', 'Check Email', '<p style="margin: 0px 0px 15px; padding: 0px; text-align: justify; font-family: ''Open Sans'', Arial, sans-serif;">Integer vehicula metus libero, vitae vehicula metus blandit non. Praesent eget scelerisque lectus. Curabitur ut velit eget enim lacinia molestie id a nulla. Nulla orci metus, feugiat non velit sollicitudin, egestas mollis mi. Aliquam rutrum ante non nunc tincidunt, eu lobortis tellus dictum. Sed cursus porta lorem, egestas efficitur velit sagittis sed. Quisque faucibus aliquet cursus. Nunc elit magna, mollis ac felis condimentum, suscipit tempor odio.</p>\r\n<p>&nbsp;</p>', '2017-01-19 09:46:34', '2017-01-24 08:25:22', 0),
(7, 'Registeration', 'Registeration', '                                                                                                        dgfdfgggggggggggggggggggg                                                                                                ', '2017-01-19 09:47:12', '2017-01-20 00:10:20', 1),
(8, 'Unassigned Tuition', 'Unassigned Tuition', '<p>Dear #fullname#, In nulla sapien, #email#&nbsp;ut luctus ut, unassigned&nbsp;tuition risus. Ut pharetra lacus at tellus cursus pulvinar. Cras placerat tortor a ligula porta, in auctor urna ultricies. Vestibulum ut justo <strong>sapien</strong>. Maecenas ligula leo, suscipit vel fermentum ac, bibendum sed dui. Regards Home Tuition</p>', '2017-01-19 09:47:35', '2017-01-19 09:47:35', 0),
(9, 'Teacher Approved', 'Teacher Approved', '<p>Dear #fullname#</p>\r\n<p>Duis ultrices non #email# eu blandit. approved&nbsp;erat volutpat. Suspendisse sit amet laoreet neque. Aliquam malesuada ut dui in commodo. Nullam quis orci luctus, pellentesque ligula vel, condimentum tellus. Duis sit amet ex eget velit elementum euismod eu sed est. Donec tincidunt dictum euismod. Vivamus leo enim, sodales in posuere et, egestas ac ipsum. Sed vel nunc id dui sollicitudin lacinia ac eu quam. Aliquam et odio nec sem dictum auctor. Vivamus euismod, arcu et varius gravida, massa nisl auctor leo, et iaculis lorem nibh eget orci. Donec vel feugiat nulla, nec iaculis massa. Praesent fringilla risus eget tortor ultrices, in interdum nulla tristique. Nullam ac leo et dolor venenatis aliquet. Morbi mattis lacinia faucibus.</p>\r\n<p>&nbsp;</p>\r\n<p>Regards</p>\r\n<p>Home Tuition</p>', '2017-01-19 09:47:46', '2017-01-25 02:08:55', 1),
(10, 'sdfsff', 'sdfsdf', 'sdfsdfsdfsf', '2017-01-19 09:47:52', '2017-01-19 09:47:52', 1),
(12, 'sdfsdf', 'sdfsdf', '                                                                     sdfsdfsdf                               ', '2017-01-19 10:13:18', '2017-01-19 10:13:18', 1),
(13, 'ffghfgh', 'fghgfhf', '<p>fghgfhfhf</p>', '2017-01-24 02:05:49', '2017-01-24 02:05:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `global_notes`
--

CREATE TABLE `global_notes` (
  `id` int(11) NOT NULL,
  `new_arrivals` text NOT NULL,
  `pending_retry` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `global_notes`
--

INSERT INTO `global_notes` (`id`, `new_arrivals`, `pending_retry`, `created_at`, `updated_at`) VALUES
(1, 'test note1\r\ntest note2', 'pending notes', '2017-05-23 02:00:53', '2017-05-23 02:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `institutes`
--

CREATE TABLE `institutes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `institutes`
--

INSERT INTO `institutes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'IUB', '2017-05-23 06:07:11', '2017-05-23 01:07:11'),
(2, 'LUMS', '2017-03-07 02:39:23', '2017-03-07 02:39:23'),
(3, 'UET', '2017-03-07 02:40:11', '2017-03-07 02:40:11'),
(4, 'NUST', '2017-03-07 02:40:19', '2017-03-07 02:40:19'),
(5, 'FAST', '2017-03-07 02:40:25', '2017-03-07 02:40:25'),
(6, 'MAJU', '2017-03-07 02:40:32', '2017-03-07 02:40:32'),
(7, 'Test', '2017-03-07 07:41:06', '2017-03-07 02:41:06');

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `labels`
--

INSERT INTO `labels` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'test01', '2016-11-21 09:16:52', '2016-11-21 04:16:52'),
(2, 'test02', '2016-11-21 04:16:37', '2016-11-21 04:16:37'),
(3, 'test03', '2016-11-21 05:25:25', '2016-11-21 05:25:25'),
(4, 'test04', '2016-11-21 05:25:36', '2016-11-21 05:25:36'),
(5, 'test05', '2016-11-21 05:25:46', '2016-11-21 05:25:46'),
(7, 'test07', '2016-11-21 05:26:10', '2016-11-21 05:26:10'),
(8, 'test08', '2016-11-21 05:26:19', '2016-11-21 05:26:19'),
(9, 'test09', '2016-11-21 05:26:28', '2016-11-21 05:26:28'),
(10, 'test10', '2016-11-21 05:26:38', '2016-11-21 05:26:38'),
(11, 'test11', '2016-11-21 05:26:57', '2016-11-21 05:26:57'),
(12, 'label12', '2016-11-24 05:02:52', '2016-11-24 05:02:52'),
(13, 'label13', '2016-11-24 10:03:15', '2016-11-24 05:03:15'),
(14, 'test13', '2016-11-28 01:59:27', '2016-11-28 01:59:27'),
(15, 'test14', '2016-11-28 01:59:40', '2016-11-28 01:59:40'),
(16, 'test15', '2016-11-28 01:59:48', '2016-11-28 01:59:48'),
(17, 'test16', '2016-11-28 01:59:59', '2016-11-28 01:59:59'),
(18, 'test17', '2016-11-28 02:00:07', '2016-11-28 02:00:07'),
(19, 'test18', '2016-11-28 02:00:17', '2016-11-28 02:00:17'),
(20, 'test19', '2016-11-28 02:00:26', '2016-11-28 02:00:26'),
(21, 'test20', '2016-11-28 02:00:36', '2016-11-28 02:00:36'),
(22, 'test21', '2016-11-28 02:00:46', '2016-11-28 02:00:46'),
(23, 'PCBML2S', '2016-12-01 18:36:59', '2016-12-01 18:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `locations` varchar(100) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `locations`, `zone_id`, `created_at`, `updated_at`) VALUES
(1, 'johar town', 1, '2016-10-27 13:50:42', '2017-04-26 02:03:33'),
(2, 'DHA', 2, '2016-10-27 08:51:24', '2017-05-23 00:10:30'),
(3, 'Gulberg', 2, '2016-10-27 08:51:59', '2017-04-04 05:04:30'),
(4, 'Mughal Pura', 1, '2016-10-27 08:52:19', '2017-04-04 05:04:45'),
(5, 'Dharam Pura', 2, '2016-10-27 08:52:29', '2017-04-04 05:04:08'),
(6, 'Garih Shahu ', 1, '2016-10-27 09:07:49', '2017-04-04 05:04:23'),
(7, 'Sadar Cantt', 3, '2016-10-27 09:08:51', '2017-04-05 00:08:46'),
(8, 'Mazang', 3, '2016-10-27 09:09:08', '2017-04-05 00:08:38'),
(9, 'Wapda Town', 3, '2016-10-27 09:09:21', '2017-04-05 00:08:28'),
(10, 'Faysal Town', 1, '2016-10-27 09:09:42', '2017-04-04 05:04:16'),
(11, 'Barkat Market', 3, '2016-10-27 09:09:58', '2017-04-05 00:08:16'),
(12, 'Main Market', 2, '2016-10-27 09:10:08', '2017-05-23 00:10:52'),
(13, 'Others', 2, '2016-11-24 05:05:39', '2017-04-04 05:05:19'),
(14, 'Test Zone', 2, '2017-04-04 04:30:35', '2017-04-04 04:30:35'),
(15, 'Saddar Cantt', 4, '2017-04-05 00:33:58', '2017-04-05 00:33:58'),
(16, 'PIA Colony', 4, '2017-04-05 00:34:30', '2017-04-05 00:34:30'),
(17, 'PCSIR I', 4, '2017-04-05 00:35:02', '2017-04-05 00:35:02'),
(18, 'Wapada Town', 4, '2017-04-05 00:47:08', '2017-04-05 00:47:08'),
(19, 'PCSIR II', 5, '2017-04-05 00:47:57', '2017-04-05 00:47:57'),
(20, 'Baharia Twon', 5, '2017-04-05 00:48:09', '2017-04-05 00:48:09'),
(21, 'EDEN', 5, '2017-04-05 00:48:41', '2017-04-05 00:48:41'),
(22, 'Lake City', 5, '2017-04-05 00:48:53', '2017-04-05 00:48:53'),
(23, 'Fazia Society', 5, '2017-04-05 00:49:08', '2017-04-05 00:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `marital_status`
--

CREATE TABLE `marital_status` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `marital_status`
--

INSERT INTO `marital_status` (`id`, `name`) VALUES
(1, 'Married'),
(2, 'Single');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_10_04_092145_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('admin@admin.com', '0388a2cd5314f3a90e44991d781a831289a3526eaf9efbd26115854f8ce76e00', '2016-10-27 05:31:27'),
('jwaseem@thinkdonesolutions.com', '7a78ac1dff3dfbbc7c6e6a45cdc6f04f1c4618d17dc4ab1f6f18377044d3152c', '2016-11-24 10:04:05'),
('jwanjum@gmail.com', '5f2c1989cdf0c68c26b419e026709a4452baf1b22d55c1798ba98e9819768c0a', '2017-02-27 01:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(9, 'administrator', '2016-10-04 05:59:04', '2016-10-04 05:59:04'),
(10, 'student postal', '2016-10-04 06:12:10', '2016-10-04 06:12:10'),
(11, 'teacher postal', '2016-10-04 06:52:28', '2016-10-04 06:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Punjab', '2017-04-20 09:34:06', '0000-00-00 00:00:00'),
(2, 'Sindh', '2017-04-20 09:34:31', '0000-00-00 00:00:00'),
(3, 'Khyber Pakhtunkhwa', '2017-04-20 09:35:58', '0000-00-00 00:00:00'),
(4, 'Balochistan', '2017-04-20 09:35:42', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `referrers`
--

CREATE TABLE `referrers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `referrers`
--

INSERT INTO `referrers` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'javed', '2017-03-08 13:35:19', '0000-00-00 00:00:00'),
(2, 'wasim', '2017-03-08 13:35:19', '0000-00-00 00:00:00'),
(5, 'test01', '2017-03-08 14:09:38', '2017-03-08 09:09:38'),
(6, 'test02', '2017-03-08 09:09:50', '2017-03-08 09:09:50'),
(7, 'test03', '2017-03-08 09:10:02', '2017-03-08 09:10:02'),
(8, 'test04', '2017-03-08 09:10:07', '2017-03-08 09:10:07'),
(9, 'test05', '2017-03-08 09:10:13', '2017-03-08 09:10:13'),
(10, 'test06', '2017-03-08 09:10:26', '2017-03-08 09:10:26'),
(11, 'test07', '2017-03-08 09:10:32', '2017-03-08 09:10:32'),
(12, 'test08', '2017-03-08 09:10:45', '2017-03-08 09:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(12, 'admin', '2016-10-04 05:59:04', '2016-10-04 05:59:04'),
(13, 'student', '2016-10-04 06:12:10', '2016-10-04 06:12:10'),
(14, 'teacher', '2016-10-04 06:52:28', '2016-10-04 06:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(9, 12),
(10, 13),
(11, 14);

-- --------------------------------------------------------

--
-- Table structure for table `special_notes`
--

CREATE TABLE `special_notes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `special_notes`
--

INSERT INTO `special_notes` (`id`, `name`, `note`, `created_at`, `updated_at`) VALUES
(1, 'test01', '                                                                                                                                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\n                                                                                                                                                ', '2016-11-08 07:55:57', '2017-05-23 00:48:08'),
(2, 'test02', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\n', '2016-11-08 07:56:14', '2016-11-08 07:56:14'),
(4, 'teat03', 'this is test note', '2016-11-08 08:19:08', '2016-11-24 05:08:22'),
(5, 'test04', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.\r\n\r\n                                                ', '2016-11-28 01:50:08', '2016-11-28 01:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gender_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `landline` varchar(20) NOT NULL,
  `mobile1` varchar(20) NOT NULL,
  `mobile2` varchar(20) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `gender_id`, `firstname`, `lastname`, `father_name`, `email`, `password`, `dob`, `landline`, `mobile1`, `mobile2`, `address_line1`, `address_line2`, `city`, `province`, `zip_code`, `country`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 196, 0, 'zain ul abidin', '', '', 'zain@gmail.com', '', '0000-00-00', '', '03059204895', '', '', '', '', '', '', '', '', '2017-06-13 06:33:45', '', '2017-06-13 06:33:45'),
(2, 197, 0, 'Ali Ahmed', '', '', 'ali@gmail.com', '', '0000-00-00', '', '331-4716890', '', '', '', '', '', '', '', '', '2017-06-13 06:48:12', '', '2017-06-13 06:48:12'),
(3, 198, 0, 'muhammad yousaf', '', '', 'yousaf@gmail.com', '', '0000-00-00', '', '331-4716890', '', '', '', '', '', '', '', '', '2017-06-15 02:35:11', '', '2017-06-15 02:35:11');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Physics', '2016-10-25 17:06:05', '2017-05-22 23:42:16'),
(2, 'Math', '2016-10-25 17:06:05', '0000-00-00 00:00:00'),
(3, 'Chemistry', '2016-10-25 17:06:05', '0000-00-00 00:00:00'),
(15, 'Biology', '2016-10-25 14:06:28', '2016-10-25 14:06:28'),
(16, 'Economics', '2016-10-25 14:06:38', '2016-10-29 00:16:56'),
(17, 'Islamiat', '2016-11-24 04:39:35', '2016-11-24 04:40:25'),
(18, 'Pakistan Studies', '2016-11-24 04:40:06', '2016-11-24 04:40:06'),
(19, 'Social Studies', '2016-12-01 18:23:52', '2016-12-01 18:23:52'),
(20, 'LAW', '2017-04-24 08:03:06', '2017-04-24 08:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `teacher_band_id` int(11) NOT NULL,
  `marital_status_id` int(11) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `registeration_no` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) NOT NULL,
  `expected_minimum_fee` int(11) NOT NULL,
  `expected_max_fee` int(11) NOT NULL,
  `religion` varchar(20) NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `strength` text NOT NULL,
  `past_experience` text NOT NULL,
  `admin_remarks` text NOT NULL,
  `about_us` text NOT NULL,
  `no_of_children` int(11) DEFAULT NULL,
  `cnic_number` varchar(100) NOT NULL,
  `cnic_front_image` varchar(45) NOT NULL,
  `cnic_back_image` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `landline` varchar(20) NOT NULL,
  `mobile1` varchar(20) NOT NULL,
  `personal_contactno2` varchar(20) NOT NULL,
  `mobile2` varchar(20) NOT NULL,
  `guardian_contact_no` varchar(100) NOT NULL,
  `emergency_contact_no` varchar(100) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) NOT NULL,
  `city` int(11) NOT NULL,
  `address_line1_p` varchar(100) NOT NULL,
  `address_line2_p` varchar(100) NOT NULL,
  `province_p` int(11) NOT NULL,
  `city_p` int(11) NOT NULL,
  `zip_code_p` varchar(100) NOT NULL,
  `country_p` varchar(100) NOT NULL,
  `province` int(11) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `other_detail` text,
  `is_active` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL,
  `suitable_timings` varchar(50) NOT NULL,
  `experience` varchar(20) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `reference_for_rent` text NOT NULL,
  `reference_gurantor` text NOT NULL,
  `livingin` varchar(100) NOT NULL,
  `visited` tinyint(2) NOT NULL,
  `accept` tinyint(2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `teacher_photo` varchar(100) DEFAULT NULL,
  `electricity_bill` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `teacher_band_id`, `marital_status_id`, `gender_id`, `firstname`, `lastname`, `fullname`, `registeration_no`, `father_name`, `expected_minimum_fee`, `expected_max_fee`, `religion`, `added_by`, `strength`, `past_experience`, `admin_remarks`, `about_us`, `no_of_children`, `cnic_number`, `cnic_front_image`, `cnic_back_image`, `email`, `password`, `dob`, `landline`, `mobile1`, `personal_contactno2`, `mobile2`, `guardian_contact_no`, `emergency_contact_no`, `address_line1`, `address_line2`, `city`, `address_line1_p`, `address_line2_p`, `province_p`, `city_p`, `zip_code_p`, `country_p`, `province`, `zip_code`, `country`, `other_detail`, `is_active`, `is_approved`, `suitable_timings`, `experience`, `age`, `reference_for_rent`, `reference_gurantor`, `livingin`, `visited`, `accept`, `created_by`, `created_at`, `updated_by`, `updated_at`, `teacher_photo`, `electricity_bill`) VALUES
(6, 1, 1, 2, 1, '', '', 'javed afaq', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', 'employee3', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at\r\n\r\nthe coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.\r\n\r\nthe coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.', 'admin remarks', 'accompanied by English versions from the 1914 translation by H. Rackham.', 0, '31202-3637444-3', 'boxed-bg.jpg', 'boxed-bg.png', 'javedafaq@gmail.com', 'manjum', '1984-12-29', '03314716897', '3314716897', '03114788589', '3314716897', '3314716891', '', 'test address', '', 126, 'line one', '', 1, 54, '5500', 'Pakistan', 2, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 1, 1, 'evening', '5', 25, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 'accompanied by English versions from the 1914 translation by H. Rackham.', 'rent', 1, 0, 'admin', '2017-06-22 06:48:43', 'admin', '2017-06-22 01:48:43', 'avatar2.png', 'boxed-bg.jpg'),
(7, 2, 2, 2, 1, 'umer', 'khan', 'umer khan', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '', '', '0', 0, '31202-3637444-3', '1485414513-arrow1.png', 'arrow1.png', 'umer@gmail.com', '$2y$10$RZA4vN2/Hlv7fjrBKZoZbO8KhBG1hk.VR3rCxueqGGluakKSrKule', '2016-03-21', '03314716897', '0331471689', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 1, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-04-24 06:15:15', 'admin', '2017-04-24 01:08:50', 'avatar3.png', ''),
(56, 47, 2, 2, 1, 'muhammad', 'yousaf', '', '1234gb', 'Muhammad Akram', 12000, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ggggggggggggggggggg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '', '', '', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'yousaf@gmail.com', 'manjum', '2016-09-27', '03314716897', '0331471689', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                        gggggggggg', 1, 1, '', '4', 0, '', '', '', 0, 0, 'admin', '2017-01-25 12:07:03', 'admin', '2017-01-25 07:07:03', 'default-50x50.gif', ''),
(57, 48, 1, 2, 1, '', '', 'wasim anjum', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', '', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'anjum@anjum.com', 'manjum', '2016-10-05', '03314716897', '0331471689', '', '03314716897', '3314716891', '', 'H.No 180 Millat Colony', '', 54, 'Lahore', '', 1, 2, '53100', 'Pakistan', 1, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ', 1, 1, 'evening', '5', 25, '', 'Reference of guarantor(Relative/Teacher/Friend) resident in Lahore (Name, Address, Mobile No, Relationship)*\r\n', 'own', 1, 1, 'admin', '2017-05-25 13:00:21', 'admin', '2017-05-25 08:00:21', 'default-50x50.gif', 'arrow1.png'),
(58, 49, 1, 2, 1, '', '', 'Amanullah Jamil', 'Unassigned', 'Muhammad Akram', 4, 8, 'Islam', 'employee2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras accumsan semper odio, auctor molestie est pharetra lobortis. Vivamus ac odio.\r\n\r\n', 'experience 1\r\n\r\nexperience 2\r\n\r\nexperience 3', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras accumsan semper odio, auctor molestie est pharetra lobortis. Vivamus ac odio.', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'jwanjum@gmail.com', 'manjum', '1984-12-10', '04211177789', '03314716897', '03114788589', '03314716897', '03314716897', '', 'test address', '', 0, 'address line one', '', 0, 0, '55334', '', 0, '63100', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras accumsan semper odio, auctor molestie est pharetra lobortis. Vivamus ac odio.', 1, 2, 'evening', '5', 25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras accumsan semper odio, auctor molestie est pharetra lobortis. Vivamus ac odio.\r\n\r\n', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras accumsan semper odio, auctor molestie est pharetra lobortis. Vivamus ac odio.', 'rent', 0, 0, 'admin', '2017-06-05 08:49:43', 'admin', '2017-06-05 03:49:43', '1484823776-avatar3.png', 'client.png'),
(59, 50, 1, 2, 1, '', '', 'Nasir Hussain', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                updating profile                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '', '', '0', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'afaq@gmail.com', '$2y$10$W.MJKzGrGp57/o2dNFeZRu6SxaFrHleo2.efmBBg0WP/qFST4hucC', '2016-10-05', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    gggggggggg', 1, 1, 'evening', '5', 25, '', '', '0', 0, 0, 'admin', '2017-05-23 13:21:41', 'admin', '2017-05-23 08:21:41', 'avatar5.png', ''),
(60, 51, 1, 2, 1, '', '', 'Ahmed Ali', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ffffffffffffffff                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       ', '', '', '0', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'ahmed@hassan.com', 'manjum', '2016-10-03', '03314716897', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ffffffffffff', 1, 1, 'evening', '5', 25, '', '', 'own', 0, 0, 'admin', '2017-05-23 13:26:06', 'admin', '2017-05-23 08:26:06', 'intro01.png', ''),
(61, 52, 10, 2, 1, 'reyan', 'ahmed', '', '1234gb', 'Muhammad Akram', 4, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', '', '', '', 0, '31202-3637444-3', '1485233354-arrow1.png', '1485233354-arrow2.png', 'reyan@reyan.com', '$2y$10$UlMBgxTGVmDVhp7H/OaIieGhIRMAFNSnDAM4vf.Bn9yXtxL0QyY8O', '2016-10-05', '03314716897', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', 1, 1, 'evening', '5', 25, '', '', '', 0, 0, 'admin', '2017-04-12 09:44:10', 'admin', '2017-04-12 04:44:10', 'intro03.png', ''),
(62, 53, 2, 2, 1, 'umair', 'ali', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 3, '3120236374443', '', '', 'umair@gmail.com', '$2y$10$us3Yd2S1.TsbizGHh5Nda.G7.KAgHzC.RaG6kN3tbhn3PvqPsrYOq', '2016-10-11', '03314716897', '03314716897', '', '03314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-22 08:27:00', NULL, ''),
(63, 54, 1, 2, 1, '', '', 'Nauman Ahmed', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ', '', '', '0', 0, '31202-3637444-3', 'arrow2.png', 'arrow1.png', 'nauman@gmail.com', 'manjum', '2016-10-10', '03314716897', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                        ', 1, 1, 'evening', '5', 25, '', '', 'own', 0, 0, 'admin', '2017-05-23 13:27:50', 'admin', '2017-05-23 08:27:50', '1484827160-avatar5.png', ''),
(64, 55, 2, 2, 1, 'umer', 'javed', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                            ', '', '', '', 0, '3120236374443', 'i.jpg', 'm.jpg', 'jw@gmail.com', '$2y$10$Zrv3PP85ZPgDL/WOcD4hiuUgh4MERLIrEN.BiJeFP7TbIFFR01hG.', '2016-10-03', '03314716897', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                            ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-24 06:32:27', NULL, ''),
(65, 56, 1, 2, 1, '', '', 'Junaid Ahmed', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', '', '                                                                                                                                                                                                                                                                                                            ', '', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'ajax@admin.com', 'manjum', '2016-09-28', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                ', 2, 1, 'evening', '5', 25, '', '', 'own', 0, 0, 'admin', '2017-05-23 13:29:52', 'admin', '2017-05-23 08:29:52', 'item-01.png', ''),
(67, 58, 2, 2, 1, 'test', 'ajax', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                            ', '', '', '', 0, '3120236374443', 'i.jpg', 'g.jpg', 'test@ajax.com', '$2y$10$Ybopt2nfBeTzeRZU7wi3ou9U.qZTqbo4JSeZ73GkOWo8Sam8DUi9e', '2020-12-10', '', '03314716890', '', '', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                            ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-24 06:33:55', NULL, ''),
(68, 59, 1, 2, 1, '', '', 'Bilal', 'test', 'test', 8, 12, 'test', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '0', '', '0', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'bilal@gmail.com', 'manjum', '2016-10-05', '', '03314716890', '', '', '', '', 'test', '', 0, '', '', 0, 0, '', '', 0, '63100', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                ', 1, 1, 'evening', '5', 25, '', '', '0', 1, 1, 'admin', '2017-06-12 09:27:59', 'admin', '2017-06-12 04:27:59', 'intro02.png', ''),
(69, 60, 2, 2, 1, 'madni', 'shukari', '', 'test', 'test', 1200, 0, 'test', '', '                                                                                                                                                                                                        ', '', '', '', 0, '3120236374443', 'i.jpg', 'g.jpg', 'madni@gmail.com', '$2y$10$TXmqW.iWZae63oYViLjbMujGH.9NnkK83N1Oxdvx5IqcP4BIzFuy6', '2016-09-29', '', '03314716890', '', '', '', '', 'test', 'test', 0, '', '', 0, 0, '', '', 0, 'test', 'test', '                                                                                                        ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-25 10:41:47', '', ''),
(70, 61, 2, 1, 1, 'rahat', 'ali', '', 'test', 'test', 1200, 0, 'test', '', '                                                                                                                                                                                                                                                                                                            ', '', '', '', 2, '3120236374443', 'i.jpg', 'g.jpg', 'rahat@ali.com', '$2y$10$0xw47N.SpH83eZBZ.hqLAO98qFGsfGIsFj7VHSFR.lfveA4QrCNR6', '2016-10-05', '', '03314716890', '', '', '', '', 'test', 'test', 0, '', '', 0, 0, '', '', 0, 'test', 'test', '                                                                                                                                                            ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-25 10:42:47', '', ''),
(71, 62, 2, 2, 1, '', '', 'Fahad Shami', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', '', '', '0', 0, '31202-3637444-3', '1477319643-i.jpg', '1477319643-g.jpg', 'zeeshan@gmail.com', '$2y$10$xjgpu6cWD5rmyIYVv4lZLuz4yBhE3H960AshYHrOsyJzU3a8mPNmW', '2016-10-05', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                            ', 2, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-05-15 11:33:32', 'admin', '2017-05-15 06:33:32', '1477319643-m.jpg', ''),
(72, 63, 1, 2, 2, '', '', 'zafar', '1234gb', 'Muhammad Akram', 8, 12, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                ', '0', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'zafar@zafar.com', 'manjum', '2016-10-05', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                ', 2, 1, 'evening', '5', 30, '', '', '0', 0, 0, 'admin', '2017-06-06 06:34:10', 'admin', '2017-06-06 01:34:10', 'avatar2.png', ''),
(73, 64, 2, 2, 1, '', '', 'hasan jamil', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                    ', '', '', '0', 0, '31202-3637444-3', '', '', 'mohsin@mohsin.com', '$2y$10$Td.384yMb2IPA7FKA6oUR.pSvp2AWSepm5p5V0OgfR9dtnIj7.V5G', '2016-10-09', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 2, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-05-15 11:33:54', 'admin', '2017-05-15 06:33:54', '', '');
INSERT INTO `teachers` (`id`, `user_id`, `teacher_band_id`, `marital_status_id`, `gender_id`, `firstname`, `lastname`, `fullname`, `registeration_no`, `father_name`, `expected_minimum_fee`, `expected_max_fee`, `religion`, `added_by`, `strength`, `past_experience`, `admin_remarks`, `about_us`, `no_of_children`, `cnic_number`, `cnic_front_image`, `cnic_back_image`, `email`, `password`, `dob`, `landline`, `mobile1`, `personal_contactno2`, `mobile2`, `guardian_contact_no`, `emergency_contact_no`, `address_line1`, `address_line2`, `city`, `address_line1_p`, `address_line2_p`, `province_p`, `city_p`, `zip_code_p`, `country_p`, `province`, `zip_code`, `country`, `other_detail`, `is_active`, `is_approved`, `suitable_timings`, `experience`, `age`, `reference_for_rent`, `reference_gurantor`, `livingin`, `visited`, `accept`, `created_by`, `created_at`, `updated_by`, `updated_at`, `teacher_photo`, `electricity_bill`) VALUES
(74, 65, 1, 2, 1, '', '', 'Farrukh Nadeem', '1234gb', 'Muhammad Akram', 12, 15, 'Islam', '', '                                                                                                                                                                                                                                                                                                            ', '0', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'farrukh@admin.com', 'manjum', '2016-09-25', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                            ', 1, 1, '', '15', 50, '', '', 'own', 0, 0, 'admin', '2017-06-08 07:31:14', 'admin', '2017-06-08 02:31:14', 'default-50x50.gif', ''),
(75, 66, 1, 2, 2, '', '', 'Tayyab', '1234gb', 'Muhammad Akram', 8, 12, 'Islam', 'employee1', '                                                                                                                                                                                                        ', '0', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'tayyab@admin.com', 'manjum', '2016-09-27', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 2, 1, 'evening', '5', 25, '', '', 'own', 1, 1, 'admin', '2017-06-07 05:28:23', 'admin', '2017-06-07 00:28:23', 'default-50x50.gif', ''),
(76, 67, 1, 2, 1, '', '', 'irfan jamil', '1234gb', 'Muhammad Akram', 4, 8, 'Islam', 'employee4', '                                                                                                                                                                                                        ', '0', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'aman@gmail.com', 'manjum', '2016-09-25', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 1, 1, 'morning', '0.5', 25, '', '', 'own', 1, 1, 'admin', '2017-06-08 07:47:28', 'admin', '2017-06-08 02:47:28', 'intro01.png', ''),
(77, 68, 1, 2, 1, '', '', 'naeem iqbal', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                    ', '0', '', '0', 0, '31202-3637444-3', '', '', 'naeem@gmail.com', 'manjum', '2016-10-05', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 1, 'evening', '0', 25, '', '', 'own', 1, 1, 'admin', '2017-06-08 07:47:53', 'admin', '2017-06-08 02:47:53', 'avatar04.png', ''),
(78, 69, 2, 2, 1, '', '', 'javed anjum', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                    ', '', '', '0', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'test11@test11.com', '$2y$10$nkC6mXIiw3VI6FfzRAbN4eO/0mCzeTSJEW4MaJVPq.vLCkGntSJjS', '2016-10-02', '', '03314716890', '', '03314716897', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 2, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-05-15 11:34:17', 'admin', '2017-05-15 06:34:17', 'm.jpg', ''),
(79, 70, 1, 2, 1, '', '', 'test11', '1234gb', 'Muhammad Akram', 8, 12, 'Islam', 'employee1', '                                                                                                    ', '0', '', '0', 0, '31202-3637444-3', '', '', 'test11@test.com', 'manjum', '2016-10-05', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 1, 'evening', '10', 35, '', '', 'own', 1, 1, 'admin', '2017-06-06 12:03:39', 'admin', '2017-06-06 07:03:39', '', ''),
(83, 74, 1, 1, 1, 'asad', 'umer', '', '1234gb', 'Muhammad Akram', 12000, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'asad@umer.com', '$2y$10$oAgjclkYKZjGtPo8f7.eIunY4kB7g9364tigcnVy9iAafpkqbNXmq', '2016-10-05', '', '3314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2017-01-26 02:13:16', 'default-50x50.gif', ''),
(84, 75, 2, 2, 2, '', '', 'Momin Ullah', '1234gb', 'Muhammad Akram', 0, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '0', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'changez@khan.com', '$2y$10$9brlWP3eEmn3yHmZU5JtdumN8j5LGsVFEeUMLIPHeIXaydYzsPnH.', '2016-10-18', '', '3314716897', '', '', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                            ', 2, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-05-15 11:34:41', 'admin', '2017-05-15 06:34:41', 'boxed-bg.png', ''),
(85, 76, 2, 2, 1, 'idress', 'lala', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 0, '3120236374443', 'i.jpg', 'g.jpg', 'idress@idress.com', '$2y$10$8YIDQY1tKZeyocsk7Ufrqe1ZwoV4WhwpmL6xnClmqunABS06V1uQe', '2016-10-05', '', '3314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-29 09:28:31', '', ''),
(86, 77, 1, 1, 1, 'azhar', 'mahmood', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 2, '3120236374443', 'i.jpg', 'g.jpg', 'azharmehmood@azhar.com', '$2y$10$Dl9m3iJeCEWvilme2K9HcuDp7qc/U4YuBUuyALDP5v9S3OEAOXbZS', '2016-10-13', '', '3314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-29 09:30:05', '', ''),
(87, 83, 5, 1, 1, 'test01', 'khan', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 3, '3120236374443', 'i.jpg', 'g.jpg', 'teat01@admin.com', '$2y$10$Fwm4.Vf06hJuXhkd0Ag6NuIJ/Gaq7X32LDKAtCnRawEzHa8CbeD8O', '2016-09-29', '', '3314716897', '', '', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-29 10:53:46', '', ''),
(93, 89, 2, 2, 1, 'mask', 'test', '', '1234gb', 'Muhammad Akram', 12003, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ', '', '', '', 4, '31202-3637444-3', 'i.jpg', 'g.jpg', 'mask@admin.com', '$2y$10$qDzpGHyIqOD9gCDHJnw8Yu7niZoPwUBL8KXq5EWDUX2UgbnW1L0WG', '2016-10-05', '', '03314716890', '', '0331471689', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '54000', 'Pakistan', '                                                                                                                                                                                                                                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-23 09:59:50', '', ''),
(97, 94, 2, 1, 1, 'warning', 'modal', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 3, '31202-3637444-3', 'i.jpg', 'g.jpg', 'warning@admin.com', '$2y$10$5qvLBCFjOjKbn.mqq/KPe.FvFWM153KNSiBJ4X3DFlYPWMpphCD7O', '2016-10-04', '', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-10-31 08:30:24', '', ''),
(98, 95, 10, 1, 1, 'test01', 'test01', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '', 3, '31202-3637444-3', 'i.jpg', 'g.jpg', 'test01@test01.com', '$2y$10$Dy7VAGs4iYs73d.hZeO60uw1srDmhGcv7V9DAGv5Sa2LSGP6ZRSJC', '2016-10-20', '', '03314716897', '', '', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', 'teacher labels', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-21 08:11:22', '', ''),
(99, 96, 2, 1, 1, 'teacher', 'labels', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '', 3, '31202-3637444-3', 'i.jpg', 'g.jpg', 'teacher@labels.com', '$2y$10$EK6nmfEl1turzVUlfXplu.UyojbJ351xD1TfBYOdvvfNiE8tV/0vG', '2016-11-01', '03314716897', '03314716897', '', '0331471689', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    test teacher labels', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-21 08:10:36', '', ''),
(102, 100, 1, 2, 1, 'html5', 'password', '', '1234gb', 'Muhammad Akram', 12000, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '', 0, '31202-3637444-3', 'i.jpg', 'g.jpg', 'html5@password.com', '$2y$10$5cUTwLVXFi21sPSSr2JVoebgXsql8A8YmWAUiyWJYKnExc0gn7nqW', '2016-11-02', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                             tt', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-23 06:37:38', '', ''),
(103, 106, 2, 1, 1, 'Nabeel', 'Khan', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                ', '', '', '', 0, '11111-1111111-1', 'arrow1.png', 'arrow2.png', 'test@testerrorss.com', '$2y$10$edyAxDZQBj/59XEIkQitzONEnT/IdOw1ACGJ.q0nOFkfLyi1.S2KC', '2016-11-14', '03314716897', '03314716897', '', '0331471689', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                        sdfsdf', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-01-26 07:23:28', 'admin', '2017-01-26 02:23:28', 'avatar3.png', ''),
(105, 108, 1, 2, 1, 'email', 'validate', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 0, '31202-3637444-3', 'default-50x50.gif', 'default-50x50.gif', 'email@validate.com', '$2y$10$VhX5KZQCz8wdtgGnFL.aFO3HVifngDuNzvBAD3FEwZMGz4a0UL/sK', '2016-11-10', '03314716897', '331', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-23 14:19:33', '', ''),
(106, 109, 1, 1, 1, 'url', 'test', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                        ', '', '', '', 3, '22222-2222222-2', 'ajax-loader.gif', 'default-50x50.gif', 'url@test.com', '$2y$10$2sLzkpwzeIVKZDD80Bz/ueYhVE5Whnr2sp0GGJyxG7KI0AkoLD//6', '2016-10-30', '03314716897', '331', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 2, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-02-28 07:52:41', 'admin', '2016-11-24 01:34:46', '', ''),
(107, 110, 2, 2, 1, 'save with', 'model', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ', '', '', '', 3, '22222-2222222-2', 'ajax-loader.gif', 'default-50x50.gif', 'save@withmodel.com', '$2y$10$6rKUq2YCmAtUs3A90e1hpuX5XMPnDv063G6aN1/25.SbZxtujuXey', '2016-11-15', '03314716897', '3314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                    tttt', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-11-24 07:18:39', 'admin', '2016-11-24 02:18:39', '', ''),
(108, 111, 1, 1, 1, 'test', 'user', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ', '', '', '', 3, '31202-3637444-5', 'default-50x50.gif', 'ajax-loader.gif', 'test@user.com', '$2y$10$VKOR2s1joMh8z/EvT96dP.lBVI1tzIVP2hOUNACWJGCYHGhCVgSIC', '2016-11-23', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-11-28 09:05:35', 'admin', '2016-11-28 04:05:35', '', ''),
(109, 116, 1, 1, 1, 'confirm', 'code', '', '1234gb', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ', '', '', '', 3, '31202-3637444-3', 'default-50x50.gif', 'default-50x50.gif', 'confirm@code.com', '$2y$10$Xz8qxvqLjHpiyk3pKuRMguHm2B9Vp/8G0twlovjQZ8oCcMHo4xpYu', '2016-11-23', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                    ', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-11-28 10:46:02', 'admin', '2016-11-28 05:46:02', '', ''),
(116, 128, 1, 2, 1, 'admin', 'user', '', '0', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ', '', '', '', 3, '11111-1111111-1', 'arrow1.png', 'arrow2.png', 'admin@user.com', '$2y$10$KzPL8IBSOqF1Yi1Q/eCRmeCMFwrmBPzkYFNaph6WZdubkRmwcStWG', '2016-11-15', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '0', 1, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-11-29 10:47:59', 'admin', '2016-11-29 05:47:59', '', ''),
(122, 147, 1, 1, 1, 'salman', 'azim', '', '0', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                            ', '', '', '', 3, '31202-3637444-3', 'arrow2.png', 'arrow1.png', 'salman_az@gmail.com', '$2y$10$ZLyj94ZcTcQpkHDWaXpxdeL2QJyumYbUwa4YxHRt75mPNZsEZpM82', '2016-11-23', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '0', 1, 0, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-11-29 14:28:38', 'admin', '2016-11-29 09:28:38', '', ''),
(123, 148, 1, 2, 1, '', '', 'yousaf javed', 'Unassigned', 'Muhammad Akram', 8, 12, 'Islam', 'employee1', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        my strengths                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '0', '', '0', 0, '31202-3637444-3', 'arrow2.png', 'arrow1.png', 'jwanjum@yahoo.com', 'manjum', '2016-11-08', '03314716897', '03314716897', '', '3314716897', '', '', 'H.No 180 Millat Colony', '', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                            ', 1, 1, '', '0.5', 0, '', '', 'own', 1, 1, 'admin', '2017-06-08 07:48:25', 'admin', '2017-06-08 02:48:25', 'avatar3.png', ''),
(125, 150, 1, 2, 1, 'junaid', 'ahmed', '', 'Unassigned', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ', '', '', '', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'junaid6095@gmail.com', '$2y$10$5sD6.POHBcXVkWmu5h.lsuPbvMcGf.OVyGbezfSzv68rSvRAWHk12', '2016-11-16', '03314716897', '331', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                        ', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-12-01 04:57:52', 'admin', '2016-11-30 23:57:52', '', ''),
(127, 152, 1, 2, 1, 'live', 'test', '', 'Unassigned', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                    ', '', '', '', 3, '31202-3637444-3', 'arrow2.png', 'arrow1.png', 'live@test.com', 'manjum', '2016-12-16', '03314716897', '331', '', '3314716897', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 1, 2, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-12-01 17:32:27', 'admin', '2016-12-01 17:32:27', 'avatar04.png', ''),
(128, 153, 1, 1, 1, 'Tayyab', 'Arif', '', 'IQB-MNT-202154', 'arif', 5000, 0, 'ISLAM', '', 'sdfdlsfkdslfdhfs dfsdfkhflsfk                                                                               ', '', '', '', 3, '35200-1455650-1', 'IMG_29112016_141315.png', 'IMG_29112016_143824.png', 'tayyabarifsheikh@yahoo.com', '123456', '2016-11-27', '465468798', '03365464796', '', '0326546547', '', '', 'H1 block johar', 'town lhaore', 0, '', '', 0, 0, '', '', 0, '45646', 'Pakistan', 'dfdsf fsdf\r\nsdf                          ', 1, 1, '', '0', 0, '', '', '', 0, 0, 'admin', '2016-12-01 19:07:20', 'admin', '2016-12-01 19:07:20', 'IMG_29112016_164051.png', ''),
(129, 154, 2, 1, 1, 'younus', 'khan', '', 'Unassigned', 'Muhammad Akram', 5000, 0, 'islam', '', '                                                                                                    ', '', '', '', 0, '11111-1111111-1', 'arrow1.png', 'arrow2.png', 'younus@younus.com', 'manjum', '1999-02-10', '03314716890', '03314716890', '', '0331471689', '', '', 'Test Address', 'Test Address', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                    ', 1, 1, '', '2', 0, '', '', '', 0, 0, 'admin', '2017-01-05 04:23:05', 'admin', '2017-01-05 04:23:05', 'avatar04.png', ''),
(130, 157, 2, 1, 1, 'is', 'active', '', 'Unassigned', 'test', 1200, 0, 'test', '', '                                                                                                            test                                                                                            ', '', '', '', 3, '31202-3637444-3', 'avatar2.png', 'avatar3.png', 'is@active.com', 'manjum', '2016-04-20', '03313424567', '0331471689', '', '0331471689', '', '', 'test', 'test', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                test', 2, 1, '', '5', 0, '', '', '', 0, 0, 'admin', '2017-02-28 08:58:54', 'admin', '2017-02-28 03:58:54', 'avatar04.png', ''),
(131, 158, 5, 2, 1, 'tuition', 'category', '', 'Unassigned', 'Muhammad Akram', 1200, 0, 'Islam', '', '                                                                                                                                                                                                                                                                                                                                              test                                                                                                                                                                                                                                                                                                                                                                              ', '', '', '', 0, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'tuition@category.com', 'manjum', '2000-03-08', '042151551555', '0331471689', '', '0331471689', '', '', 'H.No 180 Millat Colony', 'behind one unit colony', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                                                                                                                                                                                                                                                        test', 1, 1, '', '5', 0, '', '', '', 0, 0, 'admin', '2017-03-06 12:28:55', 'admin', '2017-03-06 07:28:55', 'avatar2.png', ''),
(133, 160, 1, 1, 1, 'preferred', 'institute', '', 'Unassigned', 'test', 1200, 0, 'Islam', '', '                                                                                               test                                                ', '', '', '', 5, '31202-3637444-3', 'arrow1.png', 'arrow2.png', 'preferred@institute', 'manjum', '2017-03-08', '121111111111111', '0331471689', '', '0331471689', '', '', 'test', 'test', 0, '', '', 0, 0, '', '', 0, '63100', 'Pakistan', '                                                                                      test                  ', 1, 1, '', '5', 0, '', '', '', 0, 0, 'admin', '2017-03-07 10:35:55', 'admin', '2017-03-07 05:35:55', 'avatar3.png', ''),
(138, 167, 1, 2, 1, '', '', 'Fahad Shami', 'Unassigned', '', 0, 0, '', '', '                                                                                                                                                                                                        ', '', '', '', 0, '', '', '', 'fahad@shami.com', 'manjum', '1970-01-01', '', '0331471689', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', 0, '', '', '                                                                                                        ', 1, 1, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-04-20 13:32:31', 'admin', '2017-04-20 08:32:31', '', ''),
(139, 168, 1, 2, 1, '', '', 'hasan jamil', 'Unassigned', '', 0, 0, '', '', '                                                                                                    ', '', '', '', 0, '', '', '', 'hasan@jamil.com', 'manjum', '1970-01-01', '', '0331471689', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', 0, '', '', '                                                    ', 1, 1, '', '0', 0, '', '', '', 0, 0, 'admin', '2017-04-20 08:33:50', 'admin', '2017-04-20 08:33:50', '', ''),
(140, 171, 1, 2, 1, '', '', 'new teacher', 'Unassigned', '', 0, 0, '', '', '', '', '', '0', 0, '', '', '', 'new@teacher.com', 'manjum', '1970-01-01', '', '0331471689', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', 0, '', '', '                                                    ', 1, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-04-23 23:58:14', 'admin', '2017-04-23 23:58:14', '', ''),
(148, 188, 1, 2, 1, '', '', 'Irfan Mughal', 'Unassigned', 'test', 15, 20, 'Islam', '', 'test', '', '', '0', 0, '11111-1111111-1', 'arrow1.png', 'arrow2.png', 'irfan@mughal.com', 'manjum', '1984-12-13', '', '33333111111', '', '', '3314716891', '', 'test', '', 54, 'test', '', 2, 126, '5500', 'Pakistan', 1, '63100', 'Pakistan', '                                                                                                                                                                                                                ', 1, 1, 'evening', '10', 30, 'test', 'test', 'own', 1, 1, 'admin', '2017-05-04 07:50:46', 'admin', '2017-05-04 02:50:46', 'avatar3.png', ''),
(149, 189, 1, 2, 1, '', '', 'Momin', 'Unassigned', '', 0, 0, '', '', '', '', '', '0', 0, '', '', '', 'momin@momin.com', 'manjum', '1983-03-31', '', '33333111111', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', 0, '', '', '                                                    ', 1, 1, '', '0', 0, '', '', '0', 0, 0, 'admin', '2017-05-15 01:27:05', 'admin', '2017-05-15 01:27:05', '', ''),
(151, 0, 1, 2, 1, '', '', 'admin remarks', 'Unassigned', 'admin', 0, 0, '', '', '', '', 'admin remarks', '0', 0, '', '', '', '', '', '1970-01-01', '', '0331471689', '', '', '', '', '', '', 0, '', '', 0, 0, '', '', 0, '', '', 'admin details', 1, 1, '', '0', 0, '', '', '0', 1, 1, 'admin', '2017-06-05 06:40:44', 'admin', '2017-06-05 01:40:44', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_applications`
--

CREATE TABLE `teacher_applications` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `application_status_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_applications`
--

INSERT INTO `teacher_applications` (`id`, `teacher_id`, `tuition_id`, `notes`, `application_status_id`, `created_at`, `updated_at`) VALUES
(27, 58, 10, '', 1, '2017-05-19 06:30:59', '2017-05-19 06:30:59'),
(28, 58, 6, 'tuition for me', 3, '2017-05-19 06:33:22', '2017-05-30 02:18:28'),
(29, 58, 23, 'tuition search by category', 1, '2017-05-19 06:34:14', '2017-05-19 06:34:14'),
(30, 58, 7, 'advanced search', 1, '2017-05-19 06:34:37', '2017-05-19 06:34:37'),
(31, 58, 79, '', 1, '2017-05-22 01:03:35', '2017-05-22 01:03:35'),
(32, 58, 13, '', 1, '2017-05-22 01:03:57', '2017-05-22 01:03:57'),
(33, 58, 9, 'tuition application status', 1, '2017-05-25 07:31:30', '2017-05-25 07:31:30'),
(34, 58, 12, 'tuition application status', 1, '2017-05-25 07:32:00', '2017-05-25 07:32:00'),
(35, 57, 6, 'application status', 4, '2017-05-25 08:14:50', '2017-06-12 06:39:08');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_bands`
--

CREATE TABLE `teacher_bands` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_bands`
--

INSERT INTO `teacher_bands` (`id`, `name`, `created_at`, `updated_at`, `display_order`) VALUES
(1, 'LEVEL ONE', '2016-10-27 15:13:33', '2017-04-26 02:04:26', 1),
(2, 'better', '2016-10-27 15:13:33', '2016-11-23 01:38:09', 2),
(5, 'Best', '2016-10-27 10:22:12', '2016-11-23 01:38:39', 3),
(10, 'others', '2016-10-29 01:17:03', '2016-11-24 04:49:12', 4),
(11, 'test01', '2016-11-24 04:48:35', '2016-11-24 04:53:35', 5),
(12, 'test02', '2016-11-24 04:53:59', '2017-05-23 00:16:31', 6);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_bookmarks`
--

CREATE TABLE `teacher_bookmarks` (
  `id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_bookmarks`
--

INSERT INTO `teacher_bookmarks` (`id`, `tuition_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(5, 8, 6, '2016-11-11 10:18:06', '2016-11-11 10:18:06'),
(6, 8, 63, '2016-11-11 10:18:11', '2016-11-11 10:18:11'),
(44, 11, 59, '2016-11-15 07:38:45', '2016-11-15 07:38:45'),
(45, 19, 70, '2016-11-22 05:37:17', '2016-11-22 05:37:17'),
(46, 25, 63, '2016-11-23 00:57:49', '2016-11-23 00:57:49'),
(47, 27, 62, '2016-11-24 04:36:00', '2016-11-24 04:36:00'),
(48, 10, 123, '2016-12-01 18:27:31', '2016-12-01 18:27:31'),
(61, 7, 6, '2016-12-28 11:27:44', '2016-12-28 11:27:44'),
(62, 7, 59, '2016-12-28 11:29:30', '2016-12-28 11:29:30'),
(63, 32, 68, '2017-01-07 04:52:49', '2017-01-07 04:52:49'),
(80, 31, 59, '2017-01-13 01:00:05', '2017-01-13 01:00:05'),
(82, 31, 6, '2017-01-13 01:34:50', '2017-01-13 01:34:50'),
(88, 33, 59, '2017-01-24 01:43:41', '2017-01-24 01:43:41'),
(89, 33, 63, '2017-01-24 01:43:43', '2017-01-24 01:43:43'),
(90, 33, 6, '2017-01-24 01:43:46', '2017-01-24 01:43:46'),
(91, 6, 7, '2017-01-24 09:32:11', '2017-01-24 09:32:11'),
(98, 7, 63, '2017-01-25 05:32:37', '2017-01-25 05:32:37'),
(105, 6, 58, '2017-01-26 00:11:21', '2017-01-26 00:11:21'),
(107, 34, 6, '2017-01-26 11:30:29', '2017-01-26 11:30:29'),
(108, 34, 7, '2017-01-26 11:30:31', '2017-01-26 11:30:31'),
(109, 34, 58, '2017-01-26 11:30:33', '2017-01-26 11:30:33'),
(110, 34, 63, '2017-01-26 11:30:36', '2017-01-26 11:30:36'),
(111, 33, 74, '2017-01-31 01:32:12', '2017-01-31 01:32:12'),
(112, 36, 6, '2017-01-31 01:56:42', '2017-01-31 01:56:42'),
(113, 36, 59, '2017-01-31 01:56:45', '2017-01-31 01:56:45'),
(114, 36, 63, '2017-01-31 01:56:47', '2017-01-31 01:56:47'),
(115, 36, 74, '2017-01-31 01:56:50', '2017-01-31 01:56:50'),
(123, 37, 6, '2017-02-13 05:53:49', '2017-02-13 05:53:49'),
(124, 37, 7, '2017-02-13 05:53:51', '2017-02-13 05:53:51'),
(125, 37, 123, '2017-02-13 05:53:53', '2017-02-13 05:53:53'),
(126, 37, 6, NULL, NULL),
(127, 37, 7, NULL, NULL),
(128, 37, 123, NULL, NULL),
(129, 37, 6, NULL, NULL),
(130, 37, 7, NULL, NULL),
(131, 37, 123, NULL, NULL),
(132, 37, 6, NULL, NULL),
(133, 42, 7, NULL, NULL),
(134, 43, 123, NULL, NULL),
(138, 45, 6, NULL, NULL),
(139, 45, 7, NULL, NULL),
(140, 45, 123, NULL, NULL),
(141, 45, 6, NULL, NULL),
(142, 45, 7, NULL, NULL),
(143, 45, 123, NULL, NULL),
(144, 45, 6, NULL, NULL),
(145, 45, 7, NULL, NULL),
(146, 45, 123, NULL, NULL),
(147, 45, 6, NULL, NULL),
(153, 46, 6, NULL, NULL),
(154, 46, 7, NULL, NULL),
(155, 46, 123, NULL, NULL),
(156, 46, 6, NULL, NULL),
(157, 46, 7, NULL, NULL),
(158, 46, 123, NULL, NULL),
(159, 46, 6, NULL, NULL),
(160, 46, 7, NULL, NULL),
(161, 46, 123, NULL, NULL),
(162, 46, 6, NULL, NULL),
(168, 47, 6, NULL, NULL),
(169, 47, 7, NULL, NULL),
(170, 47, 123, NULL, NULL),
(171, 47, 6, NULL, NULL),
(172, 47, 7, NULL, NULL),
(173, 47, 123, NULL, NULL),
(174, 47, 6, NULL, NULL),
(175, 47, 7, NULL, NULL),
(176, 47, 123, NULL, NULL),
(177, 47, 6, NULL, NULL),
(183, 48, 6, NULL, NULL),
(184, 48, 7, NULL, NULL),
(185, 48, 123, NULL, NULL),
(186, 48, 6, NULL, NULL),
(187, 48, 7, NULL, NULL),
(188, 48, 123, NULL, NULL),
(189, 48, 6, NULL, NULL),
(190, 48, 7, NULL, NULL),
(191, 48, 123, NULL, NULL),
(192, 48, 6, NULL, NULL),
(198, 50, 6, NULL, NULL),
(199, 50, 7, NULL, NULL),
(200, 50, 123, NULL, NULL),
(201, 50, 6, NULL, NULL),
(202, 50, 7, NULL, NULL),
(203, 50, 123, NULL, NULL),
(204, 50, 6, NULL, NULL),
(205, 50, 7, NULL, NULL),
(206, 50, 123, NULL, NULL),
(207, 50, 6, NULL, NULL),
(213, 51, 6, NULL, NULL),
(214, 51, 7, NULL, NULL),
(215, 51, 123, NULL, NULL),
(216, 51, 6, NULL, NULL),
(217, 51, 7, NULL, NULL),
(218, 51, 123, NULL, NULL),
(219, 51, 6, NULL, NULL),
(220, 51, 7, NULL, NULL),
(221, 51, 123, NULL, NULL),
(222, 51, 6, NULL, NULL),
(223, 54, 59, NULL, NULL),
(224, 54, 6, NULL, NULL),
(225, 61, 6, '2017-03-01 02:44:48', '2017-03-01 02:44:48'),
(227, 59, 123, '2017-03-01 02:45:02', '2017-03-01 02:45:02'),
(229, 75, 6, NULL, NULL),
(230, 76, 6, '2017-03-10 05:46:24', '2017-03-10 05:46:24'),
(231, 79, 6, '2017-03-22 04:12:38', '2017-03-22 04:12:38'),
(232, 66, 6, '2017-04-03 00:19:14', '2017-04-03 00:19:14'),
(233, 89, 6, NULL, NULL),
(234, 90, 6, NULL, NULL),
(235, 66, 59, '2017-04-05 04:20:43', '2017-04-05 04:20:43'),
(236, 39, 61, '2017-04-12 04:45:20', '2017-04-12 04:45:20'),
(237, 30, 68, '2017-04-12 05:04:59', '2017-04-12 05:04:59'),
(238, 30, 63, '2017-04-12 10:42:01', '2017-04-12 10:42:01'),
(240, 6, 6, '2017-05-23 05:24:29', '2017-05-23 05:24:29'),
(241, 57, 6, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(242, 57, 57, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(243, 57, 58, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(244, 57, 59, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(245, 57, 60, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(246, 57, 63, '2017-06-06 00:23:06', '2017-06-06 00:23:06'),
(247, 57, 65, '2017-06-06 00:23:07', '2017-06-06 00:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_globals`
--

CREATE TABLE `teacher_globals` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_globals`
--

INSERT INTO `teacher_globals` (`id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 6, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(2, 57, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(3, 58, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(4, 59, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(5, 60, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(6, 63, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(7, 65, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(8, 68, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(9, 72, '2017-06-06 00:19:56', '2017-06-06 00:19:56'),
(10, 74, '2017-06-06 00:19:56', '2017-06-06 00:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_institute_preferences`
--

CREATE TABLE `teacher_institute_preferences` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `institute_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_institute_preferences`
--

INSERT INTO `teacher_institute_preferences` (`id`, `teacher_id`, `institute_id`, `created_at`, `updated_at`) VALUES
(8, 132, 1, '2017-03-07 04:44:04', '2017-03-07 04:44:04'),
(9, 132, 6, '2017-03-07 04:44:04', '2017-03-07 04:44:04'),
(26, 133, 1, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(27, 133, 3, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(28, 133, 4, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(29, 133, 6, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(44, 61, 3, '2017-03-15 08:13:58', '2017-03-15 08:13:58'),
(45, 72, 3, '2017-03-15 08:14:30', '2017-03-15 08:14:30'),
(46, 59, 1, '2017-04-05 04:18:22', '2017-04-05 04:18:22'),
(47, 59, 2, '2017-04-05 04:18:22', '2017-04-05 04:18:22'),
(48, 59, 3, '2017-04-05 04:18:22', '2017-04-05 04:18:22'),
(49, 59, 5, '2017-04-05 04:18:22', '2017-04-05 04:18:22'),
(50, 68, 1, '2017-04-12 05:01:15', '2017-04-12 05:01:15'),
(84, 144, 1, '2017-05-04 01:05:00', '2017-05-04 01:05:00'),
(85, 145, 1, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(86, 145, 3, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(87, 145, 5, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(88, 146, 1, '2017-05-04 02:05:01', '2017-05-04 02:05:01'),
(89, 146, 3, '2017-05-04 02:05:01', '2017-05-04 02:05:01'),
(132, 60, 1, '2017-05-23 08:26:07', '2017-05-23 08:26:07'),
(133, 60, 2, '2017-05-23 08:26:07', '2017-05-23 08:26:07'),
(134, 60, 3, '2017-05-23 08:26:07', '2017-05-23 08:26:07'),
(135, 63, 1, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(136, 63, 2, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(137, 63, 3, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(138, 65, 1, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(139, 65, 2, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(140, 65, 3, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(141, 58, 5, '2017-05-25 00:36:13', '2017-05-25 00:36:13'),
(142, 58, 1, '2017-05-25 00:36:14', '2017-05-25 00:36:14'),
(143, 58, 2, '2017-05-25 00:36:14', '2017-05-25 00:36:14'),
(144, 58, 6, '2017-05-25 00:36:14', '2017-05-25 00:36:14'),
(145, 58, 4, '2017-05-25 00:36:14', '2017-05-25 00:36:14'),
(146, 58, 3, '2017-05-25 00:36:14', '2017-05-25 00:36:14'),
(147, 57, 5, '2017-05-25 08:00:44', '2017-05-25 08:00:44'),
(148, 57, 1, '2017-05-25 08:00:45', '2017-05-25 08:00:45'),
(149, 57, 2, '2017-05-25 08:00:45', '2017-05-25 08:00:45'),
(150, 57, 3, '2017-05-25 08:00:45', '2017-05-25 08:00:45'),
(151, 6, 2, '2017-06-05 05:16:18', '2017-06-05 05:16:18'),
(152, 6, 3, '2017-06-05 05:16:18', '2017-06-05 05:16:18'),
(153, 6, 4, '2017-06-05 05:16:18', '2017-06-05 05:16:18'),
(154, 6, 5, '2017-06-05 05:16:18', '2017-06-05 05:16:18'),
(156, 75, 5, '2017-06-06 05:04:43', '2017-06-06 05:04:43'),
(157, 75, 6, '2017-06-06 05:04:43', '2017-06-06 05:04:43'),
(158, 76, 1, '2017-06-06 05:05:58', '2017-06-06 05:05:58'),
(159, 76, 6, '2017-06-06 05:05:58', '2017-06-06 05:05:58'),
(160, 77, 1, '2017-06-06 05:07:29', '2017-06-06 05:07:29'),
(161, 79, 1, '2017-06-06 07:03:40', '2017-06-06 07:03:40'),
(162, 79, 2, '2017-06-06 07:03:40', '2017-06-06 07:03:40');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_labels`
--

CREATE TABLE `teacher_labels` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_labels`
--

INSERT INTO `teacher_labels` (`id`, `teacher_id`, `label_id`, `created_at`, `updated_at`) VALUES
(1, 99, 1, '2016-11-21 08:09:23', '2016-11-21 08:09:23'),
(2, 99, 2, '2016-11-21 08:09:23', '2016-11-21 08:09:23'),
(4, 98, 2, '2016-11-21 08:11:22', '2016-11-21 08:11:22'),
(5, 98, 4, '2016-11-21 08:11:22', '2016-11-21 08:11:22'),
(11, 100, 3, '2016-11-23 05:43:18', '2016-11-23 05:43:18'),
(12, 100, 4, '2016-11-23 05:43:18', '2016-11-23 05:43:18'),
(13, 101, 2, '2016-11-23 05:53:19', '2016-11-23 05:53:19'),
(14, 101, 5, '2016-11-23 05:53:19', '2016-11-23 05:53:19'),
(15, 102, 5, '2016-11-23 06:35:19', '2016-11-23 06:35:19'),
(17, 104, 5, '2016-11-23 10:57:45', '2016-11-23 10:57:45'),
(22, 106, 3, '2016-11-24 01:33:48', '2016-11-24 01:33:48'),
(23, 106, 5, '2016-11-24 01:33:48', '2016-11-24 01:33:48'),
(32, 107, 2, '2016-11-24 02:18:39', '2016-11-24 02:18:39'),
(33, 107, 3, '2016-11-24 02:18:39', '2016-11-24 02:18:39'),
(34, 107, 5, '2016-11-24 02:18:39', '2016-11-24 02:18:39'),
(41, 108, 2, '2016-11-28 04:05:36', '2016-11-28 04:05:36'),
(42, 108, 4, '2016-11-28 04:05:36', '2016-11-28 04:05:36'),
(44, 103, 3, '2016-11-28 05:22:40', '2016-11-28 05:22:40'),
(45, 103, 4, '2016-11-28 05:22:40', '2016-11-28 05:22:40'),
(48, 109, 2, '2016-11-28 05:46:02', '2016-11-28 05:46:02'),
(51, 110, 3, '2016-11-29 00:54:42', '2016-11-29 00:54:42'),
(52, 110, 7, '2016-11-29 00:54:42', '2016-11-29 00:54:42'),
(56, 116, 3, '2016-11-29 05:45:46', '2016-11-29 05:45:46'),
(60, 128, 19, '2016-12-01 19:07:20', '2016-12-01 19:07:20'),
(61, 128, 23, '2016-12-01 19:07:20', '2016-12-01 19:07:20'),
(66, 56, 3, '2016-12-27 20:06:54', '2016-12-27 20:06:54'),
(67, 56, 4, '2016-12-27 20:06:54', '2016-12-27 20:06:54'),
(68, 7, 2, '2016-12-27 21:37:51', '2016-12-27 21:37:51'),
(69, 7, 5, '2016-12-27 21:37:51', '2016-12-27 21:37:51'),
(92, 129, 4, '2017-01-05 04:23:05', '2017-01-05 04:23:05'),
(93, 129, 7, '2017-01-05 04:23:05', '2017-01-05 04:23:05'),
(104, 130, 3, '2017-02-28 03:58:54', '2017-02-28 03:58:54'),
(105, 130, 4, '2017-02-28 03:58:54', '2017-02-28 03:58:54'),
(106, 132, 2, '2017-03-07 04:44:03', '2017-03-07 04:44:03'),
(107, 132, 3, '2017-03-07 04:44:03', '2017-03-07 04:44:03'),
(113, 133, 2, '2017-03-07 05:35:55', '2017-03-07 05:35:55'),
(217, 61, 2, '2017-04-12 04:44:10', '2017-04-12 04:44:10'),
(218, 61, 3, '2017-04-12 04:44:10', '2017-04-12 04:44:10'),
(219, 61, 5, '2017-04-12 04:44:10', '2017-04-12 04:44:10'),
(289, 144, 2, '2017-05-04 01:05:00', '2017-05-04 01:05:00'),
(290, 144, 4, '2017-05-04 01:05:00', '2017-05-04 01:05:00'),
(293, 145, 2, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(294, 145, 4, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(295, 146, 2, '2017-05-04 02:15:51', '2017-05-04 02:15:51'),
(296, 146, 4, '2017-05-04 02:15:51', '2017-05-04 02:15:51'),
(297, 59, 1, '2017-05-23 08:21:02', '2017-05-23 08:21:02'),
(298, 59, 2, '2017-05-23 08:21:02', '2017-05-23 08:21:02'),
(299, 59, 4, '2017-05-23 08:21:02', '2017-05-23 08:21:02'),
(300, 58, 3, '2017-05-23 08:22:44', '2017-05-23 08:22:44'),
(301, 58, 4, '2017-05-23 08:22:44', '2017-05-23 08:22:44'),
(302, 58, 10, '2017-05-23 08:22:44', '2017-05-23 08:22:44'),
(303, 60, 1, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(304, 60, 2, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(305, 60, 3, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(306, 60, 4, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(307, 63, 2, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(308, 63, 4, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(309, 65, 1, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(310, 65, 2, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(311, 65, 3, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(312, 57, 2, '2017-05-25 07:58:30', '2017-05-25 07:58:30'),
(313, 57, 5, '2017-05-25 07:58:30', '2017-05-25 07:58:30'),
(327, 6, 1, '2017-06-05 07:02:40', '2017-06-05 07:02:40'),
(328, 6, 2, '2017-06-05 07:02:40', '2017-06-05 07:02:40'),
(329, 6, 4, '2017-06-05 07:02:40', '2017-06-05 07:02:40'),
(361, 79, 5, '2017-06-06 07:03:39', '2017-06-06 07:03:39'),
(362, 79, 7, '2017-06-06 07:03:40', '2017-06-06 07:03:40'),
(363, 79, 8, '2017-06-06 07:03:40', '2017-06-06 07:03:40'),
(364, 75, 1, '2017-06-07 00:28:24', '2017-06-07 00:28:24'),
(365, 75, 2, '2017-06-07 00:28:24', '2017-06-07 00:28:24'),
(366, 75, 3, '2017-06-07 00:28:24', '2017-06-07 00:28:24'),
(367, 75, 4, '2017-06-07 00:28:24', '2017-06-07 00:28:24'),
(368, 74, 1, '2017-06-08 02:31:14', '2017-06-08 02:31:14'),
(369, 74, 2, '2017-06-08 02:31:14', '2017-06-08 02:31:14'),
(370, 74, 4, '2017-06-08 02:31:14', '2017-06-08 02:31:14'),
(372, 76, 1, '2017-06-08 02:47:28', '2017-06-08 02:47:28'),
(373, 76, 2, '2017-06-08 02:47:28', '2017-06-08 02:47:28'),
(374, 76, 3, '2017-06-08 02:47:28', '2017-06-08 02:47:28'),
(375, 77, 2, '2017-06-08 02:47:53', '2017-06-08 02:47:53'),
(376, 123, 5, '2017-06-08 02:48:25', '2017-06-08 02:48:25'),
(377, 123, 7, '2017-06-08 02:48:25', '2017-06-08 02:48:25'),
(378, 123, 13, '2017-06-08 02:48:25', '2017-06-08 02:48:25'),
(379, 68, 4, '2017-06-12 04:28:00', '2017-06-12 04:28:00'),
(380, 68, 11, '2017-06-12 04:28:00', '2017-06-12 04:28:00'),
(381, 68, 12, '2017-06-12 04:28:00', '2017-06-12 04:28:00');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_location_preferences`
--

CREATE TABLE `teacher_location_preferences` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_location_preferences`
--

INSERT INTO `teacher_location_preferences` (`id`, `teacher_id`, `location_id`, `zoneid`, `created_at`, `updated_at`) VALUES
(6, 7, 9, 0, '2016-10-31 00:29:59', '2016-10-31 00:29:59'),
(7, 7, 11, 0, '2016-10-31 00:29:59', '2016-10-31 00:29:59'),
(8, 7, 7, 0, '2016-10-31 00:30:08', '2016-10-31 00:30:08'),
(11, 63, 1, 0, '2016-11-10 01:16:29', '2016-11-10 01:16:29'),
(12, 63, 11, 0, '2016-11-10 01:16:30', '2016-11-10 01:16:30'),
(13, 63, 5, 0, '2016-11-10 01:16:53', '2016-11-10 01:16:53'),
(14, 63, 8, 0, '2016-11-10 01:16:53', '2016-11-10 01:16:53'),
(15, 62, 9, 0, '2016-11-10 01:18:46', '2016-11-10 01:18:46'),
(16, 62, 11, 0, '2016-11-10 01:18:46', '2016-11-10 01:18:46'),
(18, 7, 1, 0, '2016-11-10 02:02:11', '2016-11-10 02:02:11'),
(19, 62, 12, 0, '2016-11-10 05:42:14', '2016-11-10 05:42:14'),
(25, 62, 2, 0, '2016-11-17 13:11:14', '2016-11-17 13:11:14'),
(28, 70, 10, 0, '2016-11-22 05:32:47', '2016-11-22 05:32:47'),
(39, 123, 1, 0, '2016-12-02 12:20:25', '2016-12-02 12:20:25'),
(40, 123, 2, 0, '2016-12-02 12:20:25', '2016-12-02 12:20:25'),
(41, 123, 10, 0, '2016-12-02 12:22:29', '2016-12-02 12:22:29'),
(97, 59, 1, 0, '2017-04-05 04:20:19', '2017-04-05 04:20:19'),
(102, 60, 1, 0, '2017-04-05 07:32:07', '2017-04-05 07:32:07'),
(103, 60, 6, 0, '2017-04-05 07:32:08', '2017-04-05 07:32:08'),
(104, 60, 13, 0, '2017-04-05 07:32:08', '2017-04-05 07:32:08'),
(105, 60, 15, 0, '2017-04-05 07:32:08', '2017-04-05 07:32:08'),
(106, 60, 23, 0, '2017-04-05 07:32:08', '2017-04-05 07:32:08'),
(107, 61, 1, 0, '2017-04-12 04:37:22', '2017-04-12 04:37:22'),
(108, 68, 1, 0, '2017-04-12 05:00:02', '2017-04-12 05:00:02'),
(109, 68, 10, 0, '2017-04-12 05:00:02', '2017-04-12 05:00:02'),
(445, 65, 1, 0, '2017-05-23 08:34:49', '2017-05-23 08:34:49'),
(446, 65, 6, 0, '2017-05-23 08:34:49', '2017-05-23 08:34:49'),
(447, 65, 7, 0, '2017-05-23 08:34:49', '2017-05-23 08:34:49'),
(448, 65, 8, 0, '2017-05-23 08:34:49', '2017-05-23 08:34:49'),
(564, 58, 1, 1, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(565, 58, 4, 1, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(566, 58, 10, 1, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(567, 58, 2, 2, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(568, 58, 5, 2, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(569, 58, 7, 3, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(570, 58, 9, 3, '2017-06-05 01:13:25', '2017-06-05 01:13:25'),
(571, 58, 15, 4, '2017-06-05 01:13:26', '2017-06-05 01:13:26'),
(572, 58, 17, 4, '2017-06-05 01:13:26', '2017-06-05 01:13:26'),
(575, 6, 1, 1, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(576, 6, 4, 1, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(577, 6, 10, 1, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(578, 6, 2, 2, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(579, 6, 5, 2, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(580, 6, 7, 3, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(581, 6, 9, 3, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(582, 6, 15, 4, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(583, 6, 18, 4, '2017-06-05 05:24:19', '2017-06-05 05:24:19'),
(587, 76, 1, 1, '2017-06-07 04:51:50', '2017-06-07 04:51:50'),
(588, 76, 3, 2, '2017-06-07 04:51:50', '2017-06-07 04:51:50'),
(589, 76, 7, 3, '2017-06-07 04:51:50', '2017-06-07 04:51:50'),
(590, 76, 17, 4, '2017-06-07 04:51:50', '2017-06-07 04:51:50'),
(591, 57, 1, 1, '2017-06-07 06:28:15', '2017-06-07 06:28:15'),
(592, 57, 2, 2, '2017-06-07 06:28:15', '2017-06-07 06:28:15'),
(593, 57, 17, 4, '2017-06-07 06:28:15', '2017-06-07 06:28:15'),
(594, 74, 2, 2, '2017-06-22 22:35:25', '2017-06-22 22:35:25'),
(595, 74, 5, 2, '2017-06-22 22:35:25', '2017-06-22 22:35:25'),
(596, 72, 7, 3, '2017-06-22 22:40:00', '2017-06-22 22:40:00'),
(597, 72, 8, 3, '2017-06-22 22:40:00', '2017-06-22 22:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_qualifications`
--

CREATE TABLE `teacher_qualifications` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `qualification_name` varchar(100) NOT NULL,
  `passing_year` varchar(30) NOT NULL,
  `institution` varchar(100) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `degree_document` varchar(100) NOT NULL,
  `highest_degree` varchar(100) NOT NULL,
  `elective_subjects` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `higher_degree` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_qualifications`
--

INSERT INTO `teacher_qualifications` (`id`, `teacher_id`, `qualification_name`, `passing_year`, `institution`, `grade`, `degree_document`, `highest_degree`, `elective_subjects`, `status`, `higher_degree`, `created_at`, `updated_at`) VALUES
(8, 7, '', '1999-2001', 'Islamia University Bahawalpur', 'A', 'arrow1.png', '', '', '', '', '2017-05-15 11:03:19', '2017-01-26 02:04:01'),
(20, 7, '', '2005-2007', 'Islamia University Bahawalpur', 'A', 'arrow2.png', '', '', '', '', '2017-05-15 11:03:19', '2017-01-26 02:04:28'),
(21, 7, '', '2014-2016', 'Islamia University Bahawalpur', 'A', '1485414388-arrow1.pn', '', '', '', '', '2017-05-15 11:03:19', '2017-01-26 02:06:28'),
(22, 56, '', '1999-2001', 'Islamia University Bahawalpur', 'A', 'i.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-22 06:14:43'),
(23, 56, '', '1999-2001', 'Islamia University Bahawalpur', 'B', 'g.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-22 06:15:45'),
(24, 56, '', '1999-2001', 'Islamia University Bahawalpur', 'C', 'm.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-22 06:15:25'),
(28, 57, 'FSC', '1999-2001', 'Islamia University Bahawalpur', 'A', 'boxed-bg.jpg', '1st', 'Computer Science', 'completed', '', '2017-06-21 11:11:07', '2017-06-21 06:11:07'),
(29, 57, 'BS(CS)', '2014-2016', 'Islamia University Bahawalpur', 'C', 'boxed-bg.png', '2nd', 'Computer Science', 'completed', '', '2017-06-21 11:11:42', '2017-06-21 06:11:42'),
(30, 57, 'MS(CS)', '1999-2001', 'Islamia University Bahawalpur', 'C', 'arrow1.png', 'other', 'Computer Science', 'completed', '', '2017-06-21 11:11:57', '2017-06-21 06:11:57'),
(31, 60, '', '2011-13', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-10-24 05:05:54'),
(32, 60, '', '2011-13', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-10-24 05:07:26'),
(33, 60, '', '2011-13', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-10-24 05:14:13'),
(34, 59, 'FSC', '2011-13', 'Islamia University Bahawalpur', 'A', 'i.jpg', '1st', 'Computer Science', 'completed', '', '2017-06-06 07:48:35', '2017-06-06 02:48:35'),
(35, 59, '', '2011-13', 'Islamia University Bahawalpur', 'B', 'g.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-24 05:22:26'),
(36, 59, '', '2011-13', 'Islamia University Bahawalpur', 'C', 'm.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-24 05:22:54'),
(37, 75, 'FSC', '1999-2001', 'Islamia University Bahawalpur', 'A', 'i.jpg', '1st', 'Computer Science', 'completed', '', '2017-06-06 13:07:34', '2017-06-06 08:07:34'),
(38, 75, '', '2011-2013', 'Islamia University Bahawalpur', 'B', 'g.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-25 10:34:42'),
(39, 58, 'BS(CS) Honor', '1999-2001', 'Islamia University Bahawalpur', 'A', 'testfile.pdf', '1st', 'Computer Science', 'completed', '', '2017-06-01 11:45:32', '2017-06-01 06:45:32'),
(40, 58, 'FSC', '2005-2007', 'Islamia University Bahawalpur', 'B', 'arrow1.png', '2nd', 'Computer Science', 'completed', '', '2017-06-06 06:05:57', '2017-06-06 01:05:57'),
(41, 58, 'MS(CS)', '1999-2001', 'Islamia University Bahawalpur', 'C', 'arrow2.png', '1st', 'Computer Science', 'continue', 'ACCA,BS(CS)', '2017-06-06 06:05:11', '2017-06-06 01:05:11'),
(42, 61, '', '1999-2001', 'Islamia University Bahawalpur', 'A', 'i.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-10-27 02:02:18'),
(44, 6, 'BS(CS) ', '1999-2001', 'iub', 'A', 'boxed-bg.jpg', '1st', 'Computer Science', 'completed', '', '2017-06-21 11:10:37', '2017-06-21 06:10:37'),
(46, 63, '', '2011-2013', 'Islamia University Bahawalpur', 'A', 'i.jpg', '', '', '', '', '2017-05-15 11:03:19', '2016-11-09 10:36:50'),
(48, 70, '', '1999-2001', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-22 05:30:26'),
(49, 70, '', '2011-2013', 'Islamia University Bahawalpur', 'B', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-22 05:30:39'),
(50, 70, '', '2011-2013', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-22 05:30:56'),
(51, 107, '', '1999-2001', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 02:46:08'),
(52, 107, '', '1999-2001', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 02:47:50'),
(53, 107, '', '1999-2001', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 02:48:10'),
(54, 65, '', '2001-2005', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 04:08:11'),
(55, 62, '', '1999-2001', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 07:37:56'),
(56, 62, '', '2014-2016', 'Islamia University Bahawalpur', 'A', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 07:38:14'),
(57, 62, '', '2001-2005', 'Islamia University Bahawalpur', 'B', '', '', '', '', '', '2017-05-15 11:03:19', '2016-11-24 07:38:32'),
(79, 122, '', '1999-2001', 'Islamia University Bahawalpur', 'A', 'arrow1.png', '', '', '', '', '2017-05-15 11:03:19', '2016-11-29 09:29:12'),
(80, 122, '', '2014-2016', 'Islamia University Bahawalpur', 'B', 'avatar04.png', '', '', '', '', '2017-05-15 11:03:19', '2016-11-29 09:29:49'),
(81, 122, '', '2014-2016', 'Islamia University Bahawalpur', 'C', 'arrow2.png', '', '', '', '', '2017-05-15 11:03:19', '2016-11-29 09:30:28'),
(97, 123, '', '2011-2013', 'Islamia University Bahawalpur', 'A', 'arrow1.png', '', '', '', '', '2017-05-15 11:03:19', '2016-12-01 16:55:35'),
(99, 123, '', '2011-2013', 'Islamia University Bahawalpur', 'A', 'arrow2.png', '', '', '', '', '2017-05-15 11:03:19', '2016-11-30 02:40:23'),
(101, 123, '', '2014-2016', 'Islamia University Bahawalpur', 'A', 'avatar04.png', '', '', '', '', '2017-05-15 11:03:19', '2016-11-30 02:42:14'),
(102, 128, '', '2001', 'Helly Collage', 'C Grade', 'IMG_29112016_164458.', '', '', '', '', '2017-05-15 11:03:19', '2016-12-01 19:10:48'),
(103, 68, '', '1999-99', 'Test', 'B', '', '', '', '', '', '2017-05-15 11:03:19', '2017-01-07 03:27:10'),
(111, 6, 'MS(SE)', '2011-2016', 'iub', 'A', 'boxed-bg.png', '2nd', 'Computer Science', 'continue', 'ACCA,BS(CS)', '2017-06-08 06:13:25', '2017-06-08 01:13:25'),
(112, 76, 'BS(CS) Honor', '2016', 'iub', 'A', 'arrow1.png', '1st', 'Computer Science', 'completed', '', '2017-06-06 08:08:19', '2017-06-06 08:08:19'),
(113, 72, 'BS(CS) Honor', '2016', 'iub', 'A', 'arrow1.png', '1st', 'Computer Science', 'completed', '', '2017-06-07 00:41:37', '2017-06-07 00:41:37');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subject_preferences`
--

CREATE TABLE `teacher_subject_preferences` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_subject_mapping_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_subject_preferences`
--

INSERT INTO `teacher_subject_preferences` (`id`, `teacher_id`, `class_subject_mapping_id`, `created_at`, `updated_at`) VALUES
(5, 7, 1, '2016-10-24 01:33:15', '2016-10-24 01:33:15'),
(7, 7, 5, '2016-10-24 02:18:37', '2016-10-24 02:18:37'),
(9, 56, 7, '2016-10-24 02:22:19', '2016-10-24 02:22:19'),
(10, 56, 9, '2016-10-24 02:23:45', '2016-10-24 02:23:45'),
(11, 7, 6, '2016-10-24 02:25:36', '2016-10-24 02:25:36'),
(13, 57, 5, '2016-10-24 02:31:57', '2016-10-24 02:31:57'),
(14, 57, 8, '2016-10-24 02:32:06', '2016-10-24 02:32:06'),
(15, 57, 1, '2016-10-24 02:32:16', '2016-10-24 02:32:16'),
(16, 57, 9, '2016-10-24 04:34:07', '2016-10-24 04:34:07'),
(26, 59, 5, '2016-10-24 04:45:02', '2016-10-24 04:45:02'),
(27, 59, 8, '2016-10-24 04:45:18', '2016-10-24 04:45:18'),
(28, 59, 1, '2016-10-24 04:45:31', '2016-10-24 04:45:31'),
(29, 60, 6, '2016-10-24 04:56:55', '2016-10-24 04:56:55'),
(30, 60, 9, '2016-10-24 04:56:10', '2016-10-24 04:56:10'),
(40, 61, 2, '2016-10-26 09:40:42', '2016-10-26 09:40:42'),
(41, 61, 1, '2016-10-26 09:40:42', '2016-10-26 09:40:42'),
(42, 61, 6, '2016-10-26 09:41:01', '2016-10-26 09:41:01'),
(43, 61, 10, '2016-10-26 09:41:13', '2016-10-26 09:41:13'),
(44, 56, 4, '2016-10-27 06:24:08', '2016-10-27 06:24:08'),
(45, 56, 10, '2016-10-27 06:24:38', '2016-10-27 06:24:38'),
(49, 62, 1, '2016-10-29 03:09:54', '2016-10-29 03:09:54'),
(50, 62, 5, '2016-10-29 03:10:09', '2016-10-29 03:10:09'),
(51, 62, 6, '2016-10-29 03:58:55', '2016-10-29 03:58:55'),
(53, 63, 4, '2016-11-10 05:52:57', '2016-11-10 05:52:57'),
(55, 59, 4, '2016-11-15 07:38:02', '2016-11-15 07:38:02'),
(56, 57, 6, '2016-11-22 00:53:39', '2016-11-22 00:53:39'),
(57, 68, 1, '2016-11-22 00:55:44', '2016-11-22 00:55:44'),
(58, 68, 5, '2016-11-22 00:56:26', '2016-11-22 00:56:26'),
(59, 68, 6, '2016-11-22 00:56:26', '2016-11-22 00:56:26'),
(60, 70, 7, '2016-11-22 05:32:13', '2016-11-22 05:32:13'),
(61, 70, 8, '2016-11-22 05:34:33', '2016-11-22 05:34:33'),
(63, 70, 9, '2016-11-22 05:36:44', '2016-11-22 05:36:44'),
(64, 99, 15, '2016-11-22 07:46:23', '2016-11-22 07:46:23'),
(65, 65, 4, '2016-11-24 03:18:58', '2016-11-24 03:18:58'),
(66, 65, 13, '2016-11-24 03:19:11', '2016-11-24 03:19:11'),
(67, 65, 1, '2016-11-24 03:53:24', '2016-11-24 03:53:24'),
(68, 65, 10, '2016-11-24 04:09:17', '2016-11-24 04:09:17'),
(69, 60, 15, '2016-11-24 06:03:28', '2016-11-24 06:03:28'),
(73, 123, 5, '2016-11-30 07:32:48', '2016-11-30 07:32:48'),
(75, 123, 12, '2016-12-01 19:13:03', '2016-12-01 19:13:03'),
(76, 123, 13, '2016-12-01 19:13:03', '2016-12-01 19:13:03'),
(81, 63, 8, '2016-12-28 03:56:28', '2016-12-28 03:56:28'),
(83, 123, 6, '2016-12-28 11:02:01', '2016-12-28 11:02:01'),
(84, 123, 12, '2016-12-28 11:03:51', '2016-12-28 11:03:51'),
(94, 68, 7, '2017-01-07 03:28:04', '2017-01-07 03:28:04'),
(96, 74, 9, '2017-01-31 01:28:17', '2017-01-31 01:28:17'),
(97, 68, 10, '2017-04-12 05:02:29', '2017-04-12 05:02:29'),
(98, 63, 10, '2017-04-12 10:40:45', '2017-04-12 10:40:45'),
(99, 63, 9, '2017-04-12 10:43:11', '2017-04-12 10:43:11'),
(100, 58, 7, '2017-04-14 08:14:29', '2017-04-14 08:14:29'),
(101, 58, 8, '2017-04-14 08:14:29', '2017-04-14 08:14:29'),
(102, 58, 9, '2017-04-14 08:14:29', '2017-04-14 08:14:29'),
(106, 58, 1, '2017-04-25 05:12:54', '2017-04-25 05:12:54'),
(110, 58, 3, '2017-04-25 05:14:16', '2017-04-25 05:14:16'),
(111, 58, 4, '2017-04-28 06:05:23', '2017-04-28 06:05:23'),
(112, 58, 5, '2017-04-28 06:05:24', '2017-04-28 06:05:24'),
(113, 58, 6, '2017-04-28 06:05:24', '2017-04-28 06:05:24'),
(114, 58, 10, '2017-05-16 07:35:13', '2017-05-16 07:35:13'),
(125, 58, 2, '2017-05-20 09:27:44', '2017-05-20 09:27:44'),
(130, 58, 15, '2017-05-20 09:38:15', '2017-05-20 09:38:15'),
(134, 58, 23, '2017-05-22 06:21:54', '2017-05-22 06:21:54'),
(142, 58, 1, '2017-05-30 00:23:34', '2017-05-30 00:23:34'),
(143, 58, 23, '2017-05-30 00:23:34', '2017-05-30 00:23:34'),
(144, 58, 31, '2017-05-30 00:23:34', '2017-05-30 00:23:34'),
(229, 6, 4, '2017-05-30 04:17:48', '2017-05-30 04:17:48'),
(230, 6, 5, '2017-05-30 04:17:49', '2017-05-30 04:17:49'),
(231, 6, 6, '2017-05-30 04:17:49', '2017-05-30 04:17:49'),
(232, 6, 8, '2017-05-30 04:20:23', '2017-05-30 04:20:23'),
(233, 6, 9, '2017-05-30 04:20:23', '2017-05-30 04:20:23'),
(234, 6, 10, '2017-05-30 04:20:23', '2017-05-30 04:20:23'),
(256, 58, 11, '2017-05-30 05:25:30', '2017-05-30 05:25:30'),
(257, 58, 12, '2017-05-30 05:25:30', '2017-05-30 05:25:30'),
(258, 58, 13, '2017-05-30 05:25:30', '2017-05-30 05:25:30'),
(263, 6, 1, '2017-05-31 02:26:01', '2017-05-31 02:26:01'),
(264, 6, 2, '2017-05-31 02:26:01', '2017-05-31 02:26:01'),
(265, 6, 23, '2017-05-31 02:26:01', '2017-05-31 02:26:01'),
(266, 6, 30, '2017-05-31 02:26:01', '2017-05-31 02:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tuition_categories`
--

CREATE TABLE `teacher_tuition_categories` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `tuition_category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher_tuition_categories`
--

INSERT INTO `teacher_tuition_categories` (`id`, `teacher_id`, `tuition_category_id`, `created_at`, `updated_at`) VALUES
(15, 131, 1, '2017-03-06 07:28:55', '2017-03-06 07:28:55'),
(16, 131, 2, '2017-03-06 07:28:56', '2017-03-06 07:28:56'),
(17, 131, 3, '2017-03-06 07:28:56', '2017-03-06 07:28:56'),
(46, 132, 1, '2017-03-07 04:44:03', '2017-03-07 04:44:03'),
(47, 132, 8, '2017-03-07 04:44:03', '2017-03-07 04:44:03'),
(58, 133, 1, '2017-03-07 05:35:55', '2017-03-07 05:35:55'),
(59, 133, 3, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(60, 133, 6, '2017-03-07 05:35:56', '2017-03-07 05:35:56'),
(69, 72, 3, '2017-03-15 08:14:30', '2017-03-15 08:14:30'),
(73, 59, 1, '2017-04-05 04:18:21', '2017-04-05 04:18:21'),
(74, 59, 2, '2017-04-05 04:18:21', '2017-04-05 04:18:21'),
(75, 61, 1, '2017-04-12 04:34:55', '2017-04-12 04:34:55'),
(76, 61, 3, '2017-04-12 04:34:55', '2017-04-12 04:34:55'),
(77, 68, 1, '2017-04-12 05:01:15', '2017-04-12 05:01:15'),
(138, 144, 1, '2017-05-04 01:05:00', '2017-05-04 01:05:00'),
(139, 145, 1, '2017-05-04 01:24:09', '2017-05-04 01:24:09'),
(140, 146, 1, '2017-05-04 02:05:01', '2017-05-04 02:05:01'),
(141, 71, 1, '2017-05-15 06:33:32', '2017-05-15 06:33:32'),
(142, 73, 1, '2017-05-15 06:33:54', '2017-05-15 06:33:54'),
(143, 78, 1, '2017-05-15 06:34:17', '2017-05-15 06:34:17'),
(144, 84, 1, '2017-05-15 06:34:42', '2017-05-15 06:34:42'),
(224, 58, 5, '2017-05-22 01:55:55', '2017-05-22 01:55:55'),
(225, 58, 7, '2017-05-22 01:55:56', '2017-05-22 01:55:56'),
(226, 58, 2, '2017-05-22 01:55:56', '2017-05-22 01:55:56'),
(227, 58, 3, '2017-05-22 01:55:56', '2017-05-22 01:55:56'),
(228, 58, 1, '2017-05-22 01:55:56', '2017-05-22 01:55:56'),
(229, 58, 13, '2017-05-22 01:55:56', '2017-05-22 01:55:56'),
(230, 60, 1, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(231, 60, 2, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(232, 60, 3, '2017-05-23 08:26:06', '2017-05-23 08:26:06'),
(233, 63, 1, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(234, 63, 2, '2017-05-23 08:27:51', '2017-05-23 08:27:51'),
(235, 65, 1, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(236, 65, 2, '2017-05-23 08:29:53', '2017-05-23 08:29:53'),
(244, 57, 2, '2017-05-25 08:08:34', '2017-05-25 08:08:34'),
(245, 57, 3, '2017-05-25 08:08:34', '2017-05-25 08:08:34'),
(246, 57, 1, '2017-05-25 08:08:34', '2017-05-25 08:08:34'),
(251, 6, 1, '2017-06-05 07:02:41', '2017-06-05 07:02:41'),
(252, 6, 2, '2017-06-05 07:02:41', '2017-06-05 07:02:41'),
(253, 6, 3, '2017-06-05 07:02:41', '2017-06-05 07:02:41'),
(254, 75, 1, '2017-06-06 05:03:30', '2017-06-06 05:03:30'),
(255, 76, 1, '2017-06-06 05:05:58', '2017-06-06 05:05:58'),
(256, 76, 2, '2017-06-06 05:05:58', '2017-06-06 05:05:58'),
(257, 77, 1, '2017-06-06 05:07:29', '2017-06-06 05:07:29'),
(258, 77, 3, '2017-06-06 05:07:29', '2017-06-06 05:07:29'),
(259, 79, 3, '2017-06-06 07:03:40', '2017-06-06 07:03:40'),
(260, 74, 1, '2017-06-08 02:31:14', '2017-06-08 02:31:14'),
(261, 123, 1, '2017-06-08 02:48:26', '2017-06-08 02:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `SiteID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tlabels`
--

CREATE TABLE `tlabels` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tlabels`
--

INSERT INTO `tlabels` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'test01', '2017-05-23 05:59:53', '2017-05-23 00:59:53'),
(2, 'test02', '2016-11-21 04:16:37', '2016-11-21 04:16:37'),
(3, 'test03', '2016-11-21 05:25:25', '2016-11-21 05:25:25'),
(4, 'test04', '2016-11-21 05:25:36', '2016-11-21 05:25:36'),
(5, 'test05', '2016-11-21 05:25:46', '2016-11-21 05:25:46'),
(7, 'test07', '2016-11-21 05:26:10', '2016-11-21 05:26:10'),
(8, 'test08', '2016-11-21 05:26:19', '2016-11-21 05:26:19'),
(9, 'test09', '2016-11-21 05:26:28', '2016-11-21 05:26:28'),
(10, 'test10', '2016-11-21 05:26:38', '2016-11-21 05:26:38'),
(11, 'test11', '2016-11-21 05:26:57', '2016-11-21 05:26:57'),
(12, 'label12', '2016-11-24 05:02:52', '2016-11-24 05:02:52'),
(13, 'label13', '2016-11-24 10:03:15', '2016-11-24 05:03:15'),
(14, 'test14', '2017-05-23 05:59:34', '2017-05-23 00:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `tuitions`
--

CREATE TABLE `tuitions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `tuition_catefory_id` int(11) NOT NULL,
  `is_created_admin` tinyint(2) NOT NULL,
  `is_active` tinyint(2) NOT NULL,
  `is_approved` tinyint(2) NOT NULL,
  `tuition_status_id` int(11) NOT NULL,
  `tuition_code` varchar(100) NOT NULL,
  `no_of_students` int(11) NOT NULL,
  `tuition_fee` int(11) NOT NULL,
  `tuition_max_fee` int(11) NOT NULL,
  `tuition_final_fee` int(11) NOT NULL,
  `band_id` int(11) NOT NULL,
  `experience` varchar(20) NOT NULL,
  `suitable_timings` varchar(50) NOT NULL,
  `teaching_duration` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `tuition_date` date NOT NULL,
  `tuition_start_date` date NOT NULL,
  `special_notes` text NOT NULL,
  `take_note` text NOT NULL,
  `address` text NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `contact_no2` varchar(20) NOT NULL,
  `teacher_gender` int(11) NOT NULL,
  `teacher_age` int(11) NOT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `referrer_id` int(11) NOT NULL,
  `details` text,
  `contact_person` varchar(100) NOT NULL,
  `partner_share` float(11,2) NOT NULL,
  `agent_one_share` float(11,2) NOT NULL,
  `agent_two_share` float(11,2) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuitions`
--

INSERT INTO `tuitions` (`id`, `student_id`, `tuition_catefory_id`, `is_created_admin`, `is_active`, `is_approved`, `tuition_status_id`, `tuition_code`, `no_of_students`, `tuition_fee`, `tuition_max_fee`, `tuition_final_fee`, `band_id`, `experience`, `suitable_timings`, `teaching_duration`, `location_id`, `tuition_date`, `tuition_start_date`, `special_notes`, `take_note`, `address`, `contact_no`, `contact_no2`, `teacher_gender`, `teacher_age`, `institution`, `referrer_id`, `details`, `contact_person`, `partner_share`, `agent_one_share`, `agent_two_share`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(6, 0, 1, 0, 1, 1, 1, 'T161100002', 5, 8, 12, 6000, 1, '5', 'evening', 60, 1, '2016-11-17', '2017-05-23', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ', '', 'H.No 180 Millat Colony', '3314716897', '', 1, 25, '', 2, '', 'reyan ahmed', 0.00, 0.00, 0.00, '', '2016-11-07 13:03:45', '', '2017-06-12 04:24:59'),
(7, 0, 1, 0, 1, 1, 0, 'T161100003', 1, 0, 0, 0, 0, '', '', 0, 5, '2016-11-10', '0000-00-00', '                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                 ', '', 'test', '03314716899', '', 1, 34, 'Wapda Town', 0, '', 'junaid', 0.00, 0.00, 0.00, '', '2016-11-08 05:29:27', '', '2016-12-28 11:29:04'),
(8, 0, 3, 0, 1, 0, 0, 'T161100004', 1, 0, 0, 0, 0, '', '', 0, 1, '2016-11-16', '0000-00-00', '                                                                                                                   tttttttttttttttttt                                                                                     ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 01:46:41', '', '2016-11-15 07:09:47'),
(9, 0, 1, 0, 1, 1, 0, 'T161100005', 1, 0, 0, 0, 0, '', '', 0, 5, '2016-11-15', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 01:51:33', '', '2016-11-14 05:41:36'),
(10, 0, 2, 0, 1, 1, 0, 'T161100006', 1, 0, 0, 0, 0, '', '', 0, 2, '2016-11-07', '0000-00-00', '                                                                                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 01:53:15', '', '2016-12-01 18:27:37'),
(11, 0, 2, 0, 1, 1, 0, 'T161100007', 1, 0, 0, 0, 0, '', '', 0, 4, '2016-11-16', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 07:32:04', '', '2016-11-15 07:38:58'),
(12, 0, 2, 0, 1, 1, 0, 'T161100008', 1, 0, 0, 0, 0, '', '', 0, 2, '2016-11-08', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                                                                                           ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 10:46:25', '', '2016-11-14 05:42:50'),
(13, 0, 1, 0, 1, 1, 0, 'T161100009', 1, 0, 0, 0, 0, '', '', 0, 2, '2016-10-31', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 13:00:39', '', '2016-11-09 08:00:39'),
(14, 0, 1, 0, 1, 1, 0, 'T161100010', 1, 0, 0, 0, 0, '', '', 0, 3, '2016-11-11', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 13:10:07', '', '2016-11-09 08:10:07'),
(15, 0, 1, 0, 1, 0, 0, 'T161100011', 3, 0, 0, 0, 0, '', '', 0, 4, '2016-11-07', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', 'Test Address', '0331478278', '', 1, 40, '', 0, '', 'bilal', 0.00, 0.00, 0.00, '', '2016-11-09 08:19:47', '', '2017-01-05 05:57:45'),
(16, 0, 2, 0, 1, 1, 0, 'T161100012', 4, 0, 0, 0, 0, '', '', 0, 12, '2016-11-07', '0000-00-00', '                                                               tttttttttttttttttt                                     ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-09 08:21:02', '', '2016-11-09 08:21:02'),
(17, 0, 1, 0, 1, 1, 0, 'T161100013', 1, 0, 0, 0, 0, '', '', 0, 4, '2016-11-15', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-15 06:59:38', '', '2016-11-15 06:59:38'),
(18, 0, 3, 0, 1, 1, 0, 'T161100014', 1, 0, 0, 0, 0, '', '', 0, 7, '2016-11-15', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-15 07:00:18', '', '2016-11-15 07:00:18'),
(19, 0, 1, 0, 1, 1, 0, 'T161100015', 1, 0, 0, 0, 0, '', '', 0, 10, '2016-11-22', '0000-00-00', '                                                                                                                                                                       tttttttttttttttttt                                                                                                                                     ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 05:18:39', '', '2016-11-25 09:23:49'),
(20, 0, 3, 0, 1, 1, 0, 'T161100016', 10, 0, 0, 0, 0, '', '', 0, 1, '2016-11-22', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 05:28:10', '', '2016-11-22 05:28:10'),
(21, 0, 1, 0, 1, 1, 0, 'T161100017', 1, 0, 0, 0, 0, '', '', 0, 5, '2016-11-22', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 06:27:34', '', '2016-11-22 06:27:34'),
(22, 0, 1, 0, 1, 1, 0, 'T161100018', 1, 0, 0, 0, 0, '', '', 0, 3, '2016-11-22', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 06:29:22', '', '2016-11-22 06:29:22'),
(23, 0, 1, 0, 1, 1, 0, 'T161100001', 1, 0, 0, 0, 0, '', '', 0, 1, '2016-11-22', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', 'test address', '0331478278', '', 1, 24, '', 0, '', 'bilal', 0.00, 0.00, 0.00, '', '2016-11-22 06:32:36', '', '2017-01-11 02:38:02'),
(24, 0, 1, 0, 1, 1, 0, 'T161100019', 1, 0, 0, 0, 0, '', '', 0, 1, '2016-11-22', '0000-00-00', '                                                                                                                                                                                                                                                                                                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                                                                                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 06:33:33', '', '2016-11-22 06:50:12'),
(25, 0, 1, 0, 0, 1, 0, 'T161100020', 1, 0, 0, 0, 0, '', '', 0, 1, '2016-11-23', '0000-00-00', '                                                                                                                                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 06:36:01', '', '2016-11-23 00:57:55'),
(26, 0, 1, 0, 1, 1, 0, 'T161100021', 12, 0, 0, 0, 0, '', '', 0, 2, '2016-11-22', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-22 07:23:40', '', '2016-11-22 07:23:40'),
(27, 0, 1, 0, 1, 1, 0, 'T161100022', 1, 0, 0, 0, 0, '', '', 0, 2, '2016-11-24', '0000-00-00', '                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-24 04:17:15', '', '2016-11-24 04:33:39'),
(28, 0, 1, 0, 1, 1, 0, 'T161100023', 1, 0, 0, 0, 0, '', '', 0, 2, '2016-11-24', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-24 04:17:53', '', '2016-11-24 05:21:15'),
(29, 0, 1, 0, 1, 1, 0, 'T161100024', 1, 0, 0, 0, 0, '', '', 0, 8, '2016-11-28', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, NULL, '', 0.00, 0.00, 0.00, '', '2016-11-28 02:29:22', '', '2016-11-28 02:29:22'),
(30, 0, 1, 0, 1, 1, 3, 'T161200025', 1, 3, 6, 7000, 1, '5', 'evening', 45, 1, '2016-12-02', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vitae mi vehicula, commodo sapien eu, interdum felis. Pellentesque id tortor vel ', '03314716890', '03314716890', 1, 45, 'iub', 5, '', 'Junaid Khan', 0.00, 0.00, 0.00, '', '2016-12-01 18:48:23', '', '2017-05-17 05:12:49'),
(31, 0, 1, 0, 1, 1, 2, 'T161200026', 1, 3, 3, 8000, 1, '', 'evening', 0, 9, '2016-12-27', '2017-05-17', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'Test Address', '03314716899', '1234567890', 1, 40, 'Wapda Town', 2, '', 'junaid', 0.00, 0.00, 0.00, '', '2016-12-27 18:20:39', '', '2017-05-17 05:13:33'),
(32, 0, 1, 0, 1, 1, 0, 'T170100027', 35, 0, 0, 0, 0, '', '', 0, 10, '2017-01-06', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', 'Test Address', '0331478278', '', 1, 40, 'test', 0, 'test', 'junaid', 0.00, 0.00, 0.00, '', '2017-01-07 02:26:04', '', '2017-01-07 03:24:10'),
(33, 0, 1, 0, 1, 1, 2, 'T170100028', 1, 0, 0, 0, 0, '', '', 0, 5, '2017-01-24', '0000-00-00', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-01-24 01:42:31', '', '2017-04-17 09:50:56'),
(34, 0, 1, 0, 1, 1, 3, 'T170100029', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-01-26', '0000-00-00', 'this is test note', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-01-26 11:30:15', '', '2017-04-17 12:02:15'),
(35, 0, 1, 0, 1, 1, 0, 'T170100030', 5, 0, 0, 0, 0, '', '', 0, 1, '2016-11-17', '0000-00-00', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ', '', 'H.No 180 Millat Colony', '3314716897', '', 1, 32, '', 0, '', 'reyan ahmed', 0.00, 0.00, 0.00, '', '2017-01-30 08:35:29', '', '2017-01-30 08:35:29'),
(36, 0, 1, 0, 1, 1, 2, 'T170100031', 1, 0, 0, 0, 0, '', '', 0, 5, '2017-01-24', '0000-00-00', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-01-31 01:48:40', '', '2017-04-17 09:50:56'),
(37, 0, 1, 0, 1, 1, 3, 'T170200032', 1, 4, 8, 5000, 1, '5', 'evening', 45, 1, '2017-02-13', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '1234567890', 1, 25, 'iub', 6, 'other detail', 'javed', 12.99, 10.99, 11.99, '', '2017-02-13 03:59:40', '', '2017-05-17 05:16:57'),
(38, 0, 1, 0, 1, 1, 3, 'T170200033', 1, 17, 30, 30000, 1, '5', '', 45, 1, '2017-02-13', '2017-05-03', '  The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test mobile', '03314716890', '', 1, 25, 'iub', 1, '', 'wasim', 30.00, 10.00, 20.00, '', '2017-02-13 04:36:44', '', '2017-05-03 04:39:24'),
(39, 0, 1, 0, 1, 1, 2, 'T170200034', 1, 4, 6, 4000, 10, '5', 'evening', 60, 1, '2017-02-13', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vitae mi vehicula, commodo sapien eu, interdum felis. Pellentesque id tortor ', '03314716890', '1234567890', 1, 25, 'iub', 1, 'other detail', 'javed wasim', 50.00, 12.99, 0.00, '', '2017-02-13 04:57:42', '', '2017-05-17 05:17:27'),
(40, 0, 1, 0, 1, 1, 3, 'T170200035', 1, 5, 0, 16000, 1, '5', '', 45, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 25, 'iub', 2, 'other detail', 'javed', 40.00, 10.00, 5.00, '', '2017-02-13 04:57:46', '', '2017-05-03 04:34:09'),
(42, 0, 1, 0, 1, 1, 2, 'T170200036', 1, 1, 100, 80000, 1, '5', '', 45, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 25, 'iub', 1, 'other detail', 'javed', 25.00, 13.00, 15.00, '', NULL, '', '2017-05-03 06:00:51'),
(43, 0, 1, 0, 1, 1, 3, 'T170200037', 1, 27, 46, 90000, 1, '5', '', 45, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 25, 'iub', 7, 'other detail', 'javed', 10.00, 12.99, 11.99, '', NULL, '', '2017-05-03 06:02:55'),
(45, 0, 1, 0, 1, 1, 2, 'T170200038', 1, 14, 24, 20000, 1, '5', '', 45, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 25, 'iub', 5, 'other detail', 'javed', 12.99, 12.99, 11.99, '', NULL, '', '2017-05-03 06:04:55'),
(46, 0, 1, 0, 1, 1, 0, 'T170200039', 1, 20, 34, 30000, 0, '5', '', 60, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 0, 'iub', 6, 'other detail', 'javed', 10.00, 10.00, 15.00, '', NULL, '', '2017-05-03 06:05:42'),
(47, 0, 1, 0, 1, 1, 0, 'T170200040', 1, 10, 20, 15000, 1, '1', '', 75, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 45, 'iub', 7, 'other detail', 'javed', 10.00, 10.99, 11.99, '', NULL, '', '2017-05-03 06:06:44'),
(48, 0, 1, 0, 1, 1, 0, 'T170200041', 1, 5, 50, 45000, 1, '10', '', 90, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 40, 'iub', 2, 'other detail', 'javed', 16.00, 10.00, 11.99, '', NULL, '', '2017-05-03 06:07:49'),
(49, 0, 2, 0, 1, 1, 0, 'T170200042', 1, 20, 56, 16000, 1, '5', '', 75, 1, '2017-02-13', '2017-05-03', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 20, 'iub', 1, 'other detail', 'wasim', 12.99, 10.99, 11.99, '', '2017-02-13 07:35:58', '', '2017-05-03 06:12:01'),
(50, 0, 1, 0, 1, 1, 0, 'T170200043', 1, 10, 45, 6000, 1, '10', '', 45, 1, '2017-02-13', '2017-05-17', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '1234567890', 1, 35, 'iub', 2, 'other detail', 'javed', 10.00, 10.99, 11.99, '', NULL, '', '2017-05-17 05:18:04'),
(51, 0, 1, 0, 1, 1, 0, 'T170200044', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-02-13', '0000-00-00', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'this is test address', '03314716890', '', 1, 56, 'iub', 0, 'other detail', 'javed', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(52, 0, 2, 0, 1, 1, 0, 'T170200045', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-02-13', '0000-00-00', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(53, 0, 2, 0, 1, 1, 0, 'T170200046', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-02-13', '0000-00-00', ' The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(54, 0, 1, 0, 1, 1, 0, 'T170200047', 1, 0, 0, 0, 0, '', '', 0, 9, '2016-12-27', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'Test Address', '03314716899', '', 1, 40, 'Wapda Town', 0, '', 'junaid', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(55, 0, 1, 0, 1, 1, 2, 'T170200048', 1, 0, 0, 0, 0, '', '', 0, 2, '1970-01-01', '0000-00-00', '                                                     The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                 ', '', 'test', '03314716890', '', 1, 0, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-02-28 00:04:31', '', '2017-05-16 04:08:32'),
(56, 0, 1, 0, 1, 1, 0, 'T170200049', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-02-08', '0000-00-00', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-02-28 02:02:03', '', '2017-02-28 02:02:03'),
(57, 0, 1, 0, 1, 1, 3, 'T170200050', 1, 1, 5, 10000, 1, '5', 'evening', 45, 1, '2017-05-12', '2017-05-15', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '1234567891', 1, 25, 'iub', 1, '', 'wasim', 12.99, 10.99, 11.99, '', '2017-02-28 02:21:36', '', '2017-06-12 05:18:09'),
(58, 0, 1, 0, 1, 1, 3, 'T170200051', 6, 52, 0, 4000, 2, '15', 'evening', 45, 3, '2017-03-07', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test address', '03314716892', '1234567890', 1, 30, '', 0, 'other detail', 'anjum', 0.00, 0.00, 0.00, '', '2017-02-28 06:21:09', '', '2017-05-23 06:00:30'),
(59, 0, 1, 0, 1, 1, 3, 'T170300052', 1, 0, 0, 0, 0, '', '', 0, 2, '2017-03-01', '0000-00-00', '                                                                                                                                                                                                                                                                                                                                                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from                                                                                                                                                                                                                                                                                                 ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-01 02:19:02', '', '2017-05-23 06:00:30'),
(60, 0, 1, 0, 1, 1, 3, 'T170300053', 1, 0, 0, 120, 0, '', '', 0, 2, '2017-03-01', '2017-04-27', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, 'iub', 0, '', 'wasim', 15.00, 15.99, 15.50, '', '2017-03-01 02:22:32', '', '2017-05-23 06:00:30'),
(61, 0, 1, 0, 1, 1, 4, 'T170300054', 1, 0, 0, 120, 0, '', '', 0, 2, '2017-03-01', '2017-04-26', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', 'test', '03314716890', '', 1, 56, '', 0, '', 'wasim', 15.00, 16.00, 17.00, '', '2017-03-01 02:30:53', '', '2017-05-22 07:59:29'),
(65, 0, 5, 0, 1, 1, 1, 'T170300055', 3, 3, 6, 5000, 5, '5', 'evening', 30, 4, '2017-03-08', '2017-05-17', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'Take Note', 'The standard chunk of Lorem Ipsum used since the 1500s', '03314716890', '1234567890', 1, 25, NULL, 0, 'test', 'wasim', 12.99, 10.00, 11.99, '', '2017-03-08 07:11:17', '', '2017-05-17 05:09:20'),
(66, 0, 1, 0, 1, 1, 1, 'T170300056', 4, 3, 5, 5000, 1, '5', 'morning', 45, 1, '2017-04-04', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'test tuition', '03314716890', '1234567890', 1, 25, NULL, 2, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', '2017-03-08 07:19:48', '', '2017-05-17 05:15:50'),
(67, 0, 1, 0, 2, 2, 2, 'T170300057', 2, 0, 0, 0, 0, '', 'evening', 0, 3, '2017-03-08', '1970-01-01', 'special requirements', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'test address', '03314716890', '1234567890', 1, 0, NULL, 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-08 07:42:59', '', '2017-06-12 06:08:03'),
(68, 0, 0, 0, 2, 2, 3, 'T170300058', 0, 0, 0, 0, 0, '', '', 0, 0, '2017-03-08', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '', '', 0, 0, NULL, 0, '', '', 0.00, 0.00, 0.00, '', '2017-03-08 07:47:20', '', '2017-03-10 04:57:16'),
(69, 0, 1, 0, 1, 1, 0, 'T170300059', 4, 0, 0, 7000, 0, '', '', 0, 2, '2017-03-08', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '03314716890', '', 1, 25, NULL, 0, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', NULL, '', '2017-05-17 05:18:58'),
(70, 0, 1, 0, 1, 1, 0, 'T170300060', 4, 0, 0, 0, 0, '', '', 0, 2, '2017-03-08', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '03314716890', '', 1, 25, NULL, 0, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(71, 0, 1, 0, 1, 1, 0, 'T170300061', 4, 0, 0, 0, 0, '', '', 0, 2, '2017-03-08', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '03314716890', '', 1, 25, NULL, 0, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(72, 0, 1, 0, 1, 1, 2, 'T170300062', 4, 0, 0, 0, 0, '', '', 0, 2, '2017-03-08', '0000-00-00', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '', '', '03314716890', '', 1, 25, NULL, 0, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', NULL, '', '0000-00-00 00:00:00'),
(73, 0, 0, 0, 2, 2, 2, 'T170300063', 0, 0, 0, 0, 0, '', '', 0, 0, '2017-03-09', '0000-00-00', '', '', '', '', '', 0, 0, NULL, 0, '', '', 0.00, 0.00, 0.00, '', '2017-03-09 05:49:43', '', '2017-03-09 06:34:00'),
(74, 0, 1, 0, 1, 1, 4, 'T170300064', 4, 3, 0, 0, 5, '5', '', 30, 2, '2017-03-09', '2017-03-16', '                                                    The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test address', '03314716890', '1234567890', 1, 20, NULL, 1, 'test details', 'javed', 0.00, 0.00, 0.00, '', '2017-03-09 07:00:26', '', '2017-03-09 08:39:23'),
(75, 0, 1, 0, 1, 1, 2, 'T170300065', 4, 3, 0, 0, 1, '5', 'evening', 45, 2, '2017-03-08', '2017-03-30', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', '', '03314716890', '1234567890', 1, 25, NULL, 2, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', '2017-03-08 07:19:48', '', '2017-03-10 05:07:00'),
(76, 0, 1, 0, 1, 1, 1, 'T170300066', 4, 3, 0, 8000, 1, '5', 'evening', 45, 2, '2017-03-10', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-10 05:41:40', '', '2017-05-17 05:19:28'),
(77, 0, 1, 0, 1, 1, 1, 'T170300067', 4, 3, 0, 0, 1, '5', 'evening', 45, 2, '2017-03-22', '2017-03-24', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-10 05:41:40', '', '2017-03-10 05:45:21'),
(78, 0, 1, 0, 1, 1, 2, 'T170300068', 4, 3, 3, 4000, 1, '5', 'evening', 60, 2, '2017-03-22', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-05-17 05:20:01'),
(79, 0, 2, 0, 1, 1, 2, 'T170300069', 2, 3, 6, 5000, 1, '5', 'evening', 60, 2, '2017-03-22', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'teake note', 'address', '03314716890', '1234567890', 1, 25, NULL, 2, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 01:09:10', '', '2017-05-17 05:20:25'),
(80, 0, 1, 0, 1, 1, 2, 'T170300070', 4, 3, 5, 6000, 1, '5', 'evening', 45, 2, '2017-03-22', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'test', 'test', '03314716890', '1234567890', 1, 25, NULL, 5, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 08:35:51', '', '2017-05-17 05:20:46'),
(81, 0, 1, 0, 1, 1, 2, 'T170400071', 4, 3, 0, 7000, 1, '5', 'evening', 60, 2, '2017-03-22', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-05-17 05:21:23'),
(82, 0, 1, 0, 1, 1, 1, 'T170400072', 4, 3, 0, 5000, 1, '5', 'evening', 45, 2, '2017-03-22', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-10 05:41:40', '', '2017-05-17 05:21:54'),
(83, 0, 1, 0, 1, 1, 2, 'T170400073', 4, 3, 0, 6000, 1, '5', 'evening', 60, 2, '2017-04-04', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-05-17 05:22:21'),
(84, 0, 1, 0, 1, 1, 3, 'T170400074', 4, 3, 0, 8000, 1, '5', 'evening', 60, 2, '2017-04-04', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-05-17 05:22:44'),
(85, 0, 1, 0, 1, 1, 4, 'T170400075', 4, 3, 0, 0, 1, '5', 'evening', 45, 2, '2017-04-04', '2017-03-24', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-10 05:41:40', '', '2017-04-11 05:24:45'),
(86, 0, 1, 0, 1, 1, 5, 'T170400076', 4, 3, 0, 0, 1, '5', 'evening', 45, 2, '2017-04-04', '2017-03-24', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-10 05:41:40', '', '2017-04-11 05:24:51'),
(87, 0, 1, 0, 1, 1, 6, 'T170400077', 4, 3, 0, 0, 1, '5', 'evening', 60, 2, '2017-04-04', '2017-03-31', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-04-11 05:24:57'),
(88, 0, 1, 0, 1, 1, 2, 'T170400078', 4, 3, 0, 0, 1, '5', 'evening', 60, 2, '2017-04-04', '2017-03-31', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', 'take note', 'test', '03314716890', '1234567890', 1, 25, NULL, 2, 'test', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-22 00:21:42', '', '2017-04-04 01:49:38'),
(89, 0, 1, 0, 1, 1, 2, 'T170400079', 4, 3, 0, 0, 1, '5', 'evening', 45, 1, '2017-03-08', '2017-03-30', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'test tuition', '03314716890', '1234567890', 1, 25, NULL, 2, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', '2017-03-08 07:19:48', '', '2017-03-15 04:05:21'),
(90, 0, 1, 0, 1, 1, 2, 'T170400080', 4, 3, 0, 5000, 1, '5', 'evening', 45, 1, '2017-04-04', '2017-05-17', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from ', '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'test tuition', '03314716890', '1234567890', 1, 25, NULL, 2, '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."\r\n\r\n', 'yousaf', 0.00, 0.00, 0.00, '', '2017-03-08 07:19:48', '', '2017-05-17 05:23:24'),
(91, 0, 1, 0, 2, 2, 2, 'T170400081', 2, 0, 0, 0, 0, '', '', 0, 3, '2017-03-08', '2017-05-16', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'test address', '03314716890', '1234567890', 1, 0, NULL, 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-03-08 07:42:59', '', '2017-05-16 07:57:46'),
(92, 0, 0, 0, 2, 2, 1, 'T170400082', 0, 0, 0, 5, 0, '', '', 0, 0, '2017-04-19', '2017-05-16', '', '', '', '', '', 0, 0, NULL, 0, '', '', 12.99, 10.99, 11.99, '', '2017-04-19 03:45:00', '', '2017-05-16 07:58:07'),
(93, 0, 1, 0, 1, 1, 2, 'T170500083', 1, 0, 0, 0, 0, '', '', 0, 1, '2017-05-16', '2017-05-16', '                                                    this is test note                                                ', '', 'test', '03314716890', '', 1, 0, 'iub', 0, '', 'wasim', 0.00, 0.00, 0.00, '', '2017-02-28 02:21:36', '', '2017-05-16 07:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_assignment_status`
--

CREATE TABLE `tuition_assignment_status` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_assignment_status`
--

INSERT INTO `tuition_assignment_status` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Unassigned', '2016-10-28 06:06:46', '2016-11-23 11:43:46'),
(2, 'Assigned', '2016-10-28 01:53:49', '2016-11-24 05:22:22'),
(3, 'Others', '2016-11-24 04:45:41', '2016-11-24 04:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_categories`
--

CREATE TABLE `tuition_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_categories`
--

INSERT INTO `tuition_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Science', '2016-11-08 06:21:13', '2017-05-23 00:47:52'),
(2, 'Arts', '2016-11-08 06:21:21', '2016-11-08 07:05:34'),
(3, 'Commerce', '2016-11-08 06:21:31', '2016-11-08 06:21:31'),
(5, 'Academy Tests', '2016-11-08 07:57:21', '2016-11-08 07:57:21'),
(6, 'Others', '2016-11-24 04:56:54', '2016-11-24 04:57:06'),
(7, 'Accounting', '2017-02-28 02:07:23', '2017-02-28 02:28:41'),
(8, 'Fine Arts', '2017-02-28 02:29:08', '2017-02-28 02:29:08'),
(10, 'test1', '2017-02-28 02:29:36', '2017-02-28 02:29:36'),
(11, 'test2', '2017-02-28 02:29:50', '2017-02-28 02:29:50'),
(14, 'test3', '2017-05-23 00:41:50', '2017-05-23 00:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_details`
--

CREATE TABLE `tuition_details` (
  `id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `class_subject_mapping_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `assign_date` date DEFAULT NULL,
  `is_trial` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_details`
--

INSERT INTO `tuition_details` (`id`, `tuition_id`, `class_subject_mapping_id`, `teacher_id`, `assign_date`, `is_trial`, `created_at`, `updated_at`) VALUES
(3, 6, 5, 7, '2017-05-29', 0, '2016-11-09 11:21:42', '2017-05-29 04:35:25'),
(4, 7, 4, 63, '2017-01-26', 0, '2016-11-09 11:21:42', '2017-01-26 12:04:12'),
(6, 8, 4, 63, NULL, 0, '2016-11-09 11:21:42', '2016-11-14 05:35:52'),
(7, 9, 7, 56, NULL, 0, '2016-11-09 11:21:42', '2016-11-14 05:41:36'),
(8, 10, 1, 123, '2016-12-01', 0, '2016-11-09 11:21:42', '2016-12-01 18:27:37'),
(9, 11, 4, 59, '2016-11-15', 0, '2016-11-09 11:21:42', '2016-11-15 07:38:58'),
(14, 12, 1, 0, NULL, 0, '2016-11-09 11:21:42', '0000-00-00 00:00:00'),
(15, 12, 2, 0, NULL, 0, '2016-11-09 11:21:42', '0000-00-00 00:00:00'),
(16, 12, 3, 0, NULL, 0, '2016-11-09 11:21:42', '0000-00-00 00:00:00'),
(17, 13, 4, 0, NULL, 0, '2016-11-09 07:56:54', '2016-11-09 07:56:54'),
(18, 13, 5, 0, NULL, 0, '2016-11-09 07:56:54', '2016-11-09 07:56:54'),
(19, 14, 2, 0, NULL, 0, '2016-11-09 08:10:07', '2016-11-09 08:10:07'),
(20, 14, 3, 0, NULL, 0, '2016-11-09 08:10:07', '2016-11-09 08:10:07'),
(21, 15, 1, 0, NULL, 0, '2016-11-09 08:19:47', '2016-11-09 08:19:47'),
(22, 16, 7, 0, NULL, 0, '2016-11-09 08:21:02', '2016-11-09 08:21:02'),
(23, 16, 10, 0, NULL, 0, '2016-11-09 08:21:02', '2016-11-09 08:21:02'),
(24, 17, 8, 0, NULL, 0, '2016-11-15 06:59:38', '2016-11-15 06:59:38'),
(25, 17, 10, 0, NULL, 0, '2016-11-15 06:59:38', '2016-11-15 06:59:38'),
(26, 18, 4, 0, NULL, 0, '2016-11-15 07:00:18', '2016-11-15 07:00:18'),
(27, 18, 6, 0, NULL, 0, '2016-11-15 07:00:18', '2016-11-15 07:00:18'),
(28, 19, 9, 0, NULL, 0, '2016-11-22 05:18:39', '2016-11-22 05:18:39'),
(29, 19, 10, 70, '2016-11-22', 0, '2016-11-22 05:18:39', '2016-11-22 05:37:33'),
(30, 20, 7, 0, NULL, 0, '2016-11-22 05:28:10', '2016-11-22 05:28:10'),
(31, 21, 1, 0, NULL, 0, '2016-11-22 06:27:34', '2016-11-22 06:27:34'),
(32, 22, 2, 0, NULL, 0, '2016-11-22 06:29:22', '2016-11-22 06:29:22'),
(33, 23, 5, 0, NULL, 0, '2016-11-22 06:32:36', '2016-11-22 06:32:36'),
(34, 24, 5, 0, NULL, 0, '2016-11-22 06:33:33', '2016-11-22 06:33:33'),
(35, 25, 4, 63, '2016-11-23', 0, '2016-11-22 06:36:01', '2016-11-23 00:57:55'),
(36, 25, 5, 0, NULL, 0, '2016-11-22 06:36:01', '2016-11-22 06:36:01'),
(37, 26, 4, 0, NULL, 0, '2016-11-22 07:23:40', '2016-11-22 07:23:40'),
(38, 27, 1, 62, '2016-11-24', 0, '2016-11-24 04:17:15', '2016-11-24 04:33:39'),
(39, 28, 1, 62, '2016-11-24', 0, '2016-11-24 04:17:53', '2016-11-24 05:21:15'),
(40, 29, 2, 0, NULL, 0, '2016-11-28 02:29:23', '2016-11-28 02:29:23'),
(41, 30, 11, 0, NULL, 0, '2016-12-01 18:48:23', '2016-12-01 18:48:23'),
(42, 30, 15, 0, NULL, 0, '2016-12-01 18:48:23', '2016-12-01 18:48:23'),
(43, 31, 4, 59, '2017-03-01', 0, '2016-12-27 18:20:39', '2017-03-01 01:04:15'),
(44, 32, 7, 68, '2017-01-06', 0, '2017-01-07 02:26:04', '2017-01-07 03:28:25'),
(45, 32, 8, 0, NULL, 0, '2017-01-07 02:26:04', '2017-01-07 02:26:04'),
(46, 32, 9, 0, NULL, 0, '2017-01-07 02:26:04', '2017-01-07 02:26:04'),
(47, 32, 10, 0, NULL, 0, '2017-01-07 02:26:04', '2017-01-07 02:26:04'),
(48, 33, 8, 63, '2017-04-20', 0, '2017-01-24 01:42:31', '2017-04-20 01:06:21'),
(49, 33, 9, 74, '2017-01-31', 0, '2017-01-24 01:42:31', '2017-01-31 01:31:11'),
(50, 34, 4, 6, '2017-01-26', 0, '2017-01-26 11:30:15', '2017-01-26 12:00:05'),
(51, 34, 5, 6, '2017-01-26', 0, '2017-01-26 11:30:15', '2017-01-26 12:00:05'),
(52, 35, 4, 0, '2017-01-28', 0, '2016-11-09 11:21:42', '2017-01-28 08:47:38'),
(53, 35, 5, 7, '2017-02-13', 0, '2016-11-09 11:21:42', '2017-02-13 05:32:51'),
(54, 36, 8, 63, '2017-01-31', 0, '2017-01-31 01:48:40', '2017-01-31 01:57:22'),
(55, 36, 9, 74, '2017-01-31', 0, '2017-01-31 01:48:40', '2017-01-31 01:57:08'),
(56, 37, 6, 6, '2017-04-24', 0, '2017-02-13 03:59:40', '2017-04-24 01:08:13'),
(57, 38, 2, 0, NULL, 0, '2017-02-13 04:36:44', '2017-02-13 04:36:44'),
(58, 39, 6, 61, '2017-04-12', 0, '2017-02-13 04:57:42', '2017-04-12 04:45:48'),
(59, 40, 6, 0, NULL, 0, '2017-02-13 04:57:46', '2017-02-13 04:57:46'),
(60, 35, 4, 0, '2017-01-28', 0, '2016-11-09 11:21:42', '2017-01-28 08:47:38'),
(63, 40, 6, 0, NULL, 0, NULL, NULL),
(64, 42, 6, 0, NULL, 0, NULL, NULL),
(65, 43, 6, 0, NULL, 0, NULL, NULL),
(68, 45, 6, 0, NULL, 0, NULL, NULL),
(69, 46, 6, 0, NULL, 0, NULL, NULL),
(70, 47, 6, 0, NULL, 0, NULL, NULL),
(71, 48, 6, 0, NULL, 0, NULL, NULL),
(72, 49, 1, 0, NULL, 0, '2017-02-13 07:35:58', '2017-02-13 07:35:58'),
(73, 50, 6, 0, NULL, 0, NULL, NULL),
(74, 51, 6, 0, NULL, 0, NULL, NULL),
(75, 52, 1, 0, NULL, 0, NULL, NULL),
(76, 53, 1, 0, NULL, 0, NULL, NULL),
(77, 54, 4, 0, NULL, 0, NULL, NULL),
(78, 55, 4, 0, NULL, 0, '2017-02-28 00:04:31', '2017-02-28 00:04:31'),
(79, 56, 4, 0, NULL, 0, '2017-02-28 02:02:03', '2017-02-28 02:02:03'),
(80, 56, 5, 0, NULL, 0, '2017-02-28 02:02:03', '2017-02-28 02:02:03'),
(81, 56, 6, 0, NULL, 0, '2017-02-28 02:02:03', '2017-02-28 02:02:03'),
(82, 57, 4, 63, '2017-06-06', 1, '2017-02-28 02:21:36', '2017-06-06 00:23:30'),
(83, 57, 5, 6, '2017-02-28', 0, '2017-02-28 02:21:36', '2017-02-28 05:01:19'),
(84, 57, 6, 6, '2017-02-28', 0, '2017-02-28 02:21:37', '2017-02-28 05:01:19'),
(88, 58, 15, 0, NULL, 0, '2017-02-28 06:21:10', '2017-02-28 06:21:10'),
(89, 59, 4, 6, '2017-03-01', 0, '2017-03-01 02:19:02', '2017-03-01 05:09:56'),
(90, 59, 5, 6, '2017-03-01', 1, '2017-03-01 02:19:02', '2017-03-01 05:09:11'),
(91, 59, 6, 6, '2017-03-01', 1, '2017-03-01 02:19:02', '2017-03-01 04:45:14'),
(92, 60, 11, 0, NULL, 0, '2017-03-01 02:22:32', '2017-03-01 02:22:32'),
(93, 61, 7, 0, NULL, 0, '2017-03-01 02:30:53', '2017-03-01 02:30:53'),
(94, 61, 8, 0, NULL, 0, '2017-03-01 02:30:53', '2017-03-01 02:30:53'),
(95, 61, 9, 0, NULL, 0, '2017-03-01 02:30:53', '2017-03-01 02:30:53'),
(96, 61, 10, 0, NULL, 0, '2017-03-01 02:30:53', '2017-03-01 02:30:53'),
(97, 65, 2, 0, NULL, 0, '2017-03-08 07:11:17', '2017-03-08 07:11:17'),
(98, 65, 3, 0, NULL, 0, '2017-03-08 07:11:17', '2017-03-08 07:11:17'),
(101, 66, 9, 0, NULL, 0, '2017-03-08 07:19:48', '2017-03-08 07:19:48'),
(102, 66, 10, 0, NULL, 0, '2017-03-08 07:19:48', '2017-03-08 07:19:48'),
(103, 69, 7, 0, NULL, 0, NULL, NULL),
(104, 69, 8, 0, NULL, 0, NULL, NULL),
(105, 69, 9, 0, NULL, 0, NULL, NULL),
(106, 69, 10, 0, NULL, 0, NULL, NULL),
(110, 70, 7, 0, NULL, 0, NULL, NULL),
(111, 70, 8, 0, NULL, 0, NULL, NULL),
(112, 70, 9, 0, NULL, 0, NULL, NULL),
(113, 70, 10, 0, NULL, 0, NULL, NULL),
(117, 71, 7, 0, NULL, 0, NULL, NULL),
(118, 71, 8, 0, NULL, 0, NULL, NULL),
(119, 71, 9, 0, NULL, 0, NULL, NULL),
(120, 71, 10, 0, NULL, 0, NULL, NULL),
(124, 72, 7, 0, NULL, 0, NULL, NULL),
(125, 72, 8, 0, NULL, 0, NULL, NULL),
(126, 72, 9, 0, NULL, 0, NULL, NULL),
(127, 72, 10, 0, NULL, 0, NULL, NULL),
(139, 73, 9, 0, NULL, 0, '2017-03-09 06:34:00', '2017-03-09 06:34:00'),
(140, 73, 7, 0, NULL, 0, '2017-03-09 06:34:00', '2017-03-09 06:34:00'),
(144, 74, 10, 0, NULL, 0, '2017-03-09 07:04:17', '2017-03-09 07:04:17'),
(145, 74, 9, 0, NULL, 0, '2017-03-09 07:04:17', '2017-03-09 07:04:17'),
(146, 74, 7, 0, NULL, 0, '2017-03-09 07:04:17', '2017-03-09 07:04:17'),
(147, 74, 8, 0, NULL, 0, '2017-03-09 07:04:17', '2017-03-09 07:04:17'),
(148, 74, 19, 0, NULL, 0, '2017-03-09 08:39:23', '2017-03-09 08:39:23'),
(149, 74, 18, 0, NULL, 0, '2017-03-09 08:39:23', '2017-03-09 08:39:23'),
(150, 74, 13, 0, NULL, 0, '2017-03-09 08:39:24', '2017-03-09 08:39:24'),
(152, 58, 1, 0, NULL, 0, '2017-03-10 00:53:05', '2017-03-10 00:53:05'),
(154, 68, 18, 0, NULL, 0, '2017-03-10 04:57:16', '2017-03-10 04:57:16'),
(155, 66, 13, 0, NULL, 0, '2017-03-10 05:07:00', '2017-03-10 05:07:00'),
(156, 75, 8, 0, NULL, 0, NULL, NULL),
(157, 75, 9, 0, NULL, 0, NULL, NULL),
(158, 75, 10, 0, NULL, 0, NULL, NULL),
(159, 75, 13, 0, NULL, 0, NULL, NULL),
(170, 77, 7, 0, NULL, 0, NULL, NULL),
(171, 77, 1, 0, NULL, 0, NULL, NULL),
(172, 77, 18, 0, NULL, 0, NULL, NULL),
(173, 77, 10, 0, NULL, 0, NULL, NULL),
(174, 77, 9, 0, NULL, 0, NULL, NULL),
(175, 77, 8, 0, NULL, 0, NULL, NULL),
(177, 76, 19, 0, NULL, 0, '2017-03-10 08:06:29', '2017-03-10 08:06:29'),
(178, 76, 6, 0, NULL, 0, '2017-03-10 08:08:03', '2017-03-10 08:08:03'),
(179, 66, 8, 59, '2017-04-05', 0, '2017-03-13 02:33:22', '2017-04-05 07:33:11'),
(180, 78, 10, 0, NULL, 0, '2017-03-22 00:21:42', '2017-03-22 00:21:42'),
(181, 78, 7, 58, '2017-04-03', 1, '2017-03-22 00:21:42', '2017-04-03 04:02:50'),
(182, 79, 10, 0, NULL, 0, '2017-03-22 01:09:10', '2017-03-22 01:09:10'),
(183, 79, 9, 0, NULL, 0, '2017-03-22 01:09:10', '2017-03-22 01:09:10'),
(184, 79, 7, 58, '2017-03-22', 1, '2017-03-22 01:10:54', '2017-03-22 04:12:59'),
(185, 79, 8, 0, NULL, 0, '2017-03-22 01:10:54', '2017-03-22 01:10:54'),
(186, 79, 7, 58, '2017-03-22', 0, '2017-03-22 01:10:58', '2017-04-05 07:39:41'),
(187, 79, 8, 0, NULL, 0, '2017-03-22 01:10:58', '2017-03-22 01:10:58'),
(188, 80, 18, 0, NULL, 0, '2017-03-22 08:35:51', '2017-03-22 08:35:51'),
(189, 80, 9, 0, NULL, 0, '2017-03-22 08:35:51', '2017-03-22 08:35:51'),
(190, 80, 10, 0, NULL, 0, '2017-03-22 08:37:58', '2017-03-22 08:37:58'),
(191, 80, 7, 0, NULL, 0, '2017-03-22 08:38:12', '2017-03-22 08:38:12'),
(192, 80, 8, 0, NULL, 0, '2017-03-22 08:39:01', '2017-03-22 08:39:01'),
(193, 30, 10, 68, '2017-04-12', 1, '2017-03-30 01:25:47', '2017-04-12 10:43:25'),
(194, 30, 9, 63, '2017-04-12', 1, '2017-03-30 01:25:47', '2017-04-12 10:43:31'),
(195, 31, 10, 0, NULL, 0, '2017-03-30 01:26:49', '2017-03-30 01:26:49'),
(196, 31, 7, 0, NULL, 0, '2017-03-30 01:26:50', '2017-03-30 01:26:50'),
(197, 37, 7, 0, NULL, 0, '2017-03-30 01:28:12', '2017-03-30 01:28:12'),
(198, 39, 10, 61, '2017-04-12', 0, '2017-03-30 01:30:24', '2017-04-12 04:45:44'),
(199, 39, 7, 0, NULL, 0, '2017-03-30 01:30:24', '2017-03-30 01:30:24'),
(200, 39, 13, 0, NULL, 0, '2017-03-30 01:30:24', '2017-03-30 01:30:24'),
(201, 81, 10, 0, NULL, 0, NULL, NULL),
(202, 81, 7, 0, NULL, 0, NULL, NULL),
(203, 82, 7, 0, NULL, 0, NULL, NULL),
(204, 82, 1, 0, NULL, 0, NULL, NULL),
(205, 82, 18, 0, NULL, 0, NULL, NULL),
(206, 82, 10, 0, NULL, 0, NULL, NULL),
(207, 82, 9, 0, NULL, 0, NULL, NULL),
(208, 82, 8, 0, NULL, 0, NULL, NULL),
(210, 83, 10, 0, NULL, 0, NULL, NULL),
(211, 83, 7, 0, NULL, 0, NULL, NULL),
(213, 84, 10, 0, NULL, 0, NULL, NULL),
(214, 84, 7, 0, NULL, 0, NULL, NULL),
(217, 85, 1, 0, NULL, 0, NULL, NULL),
(218, 85, 18, 0, NULL, 0, NULL, NULL),
(219, 85, 10, 0, NULL, 0, NULL, NULL),
(220, 85, 9, 0, NULL, 0, NULL, NULL),
(221, 85, 8, 0, NULL, 0, NULL, NULL),
(223, 86, 7, 0, NULL, 0, NULL, NULL),
(224, 86, 1, 0, NULL, 0, NULL, NULL),
(225, 86, 18, 0, NULL, 0, NULL, NULL),
(226, 86, 10, 0, NULL, 0, NULL, NULL),
(227, 86, 9, 0, NULL, 0, NULL, NULL),
(228, 86, 8, 0, NULL, 0, NULL, NULL),
(230, 87, 10, 0, NULL, 0, NULL, NULL),
(231, 87, 7, 0, NULL, 0, NULL, NULL),
(233, 88, 10, 0, NULL, 0, NULL, NULL),
(234, 88, 7, 0, NULL, 0, NULL, NULL),
(236, 89, 9, 0, NULL, 0, NULL, NULL),
(237, 89, 10, 0, NULL, 0, NULL, NULL),
(238, 89, 13, 0, NULL, 0, NULL, NULL),
(239, 89, 8, 0, NULL, 0, NULL, NULL),
(243, 90, 9, 0, NULL, 0, NULL, NULL),
(244, 90, 10, 0, NULL, 0, NULL, NULL),
(245, 90, 13, 0, NULL, 0, NULL, NULL),
(246, 90, 8, 0, NULL, 0, NULL, NULL),
(247, 38, 1, 0, NULL, 0, '2017-04-18 00:29:34', '2017-04-18 00:29:34'),
(248, 65, 1, 0, NULL, 0, '2017-04-18 00:32:06', '2017-04-18 00:32:06'),
(249, 93, 4, 0, NULL, 0, NULL, NULL),
(250, 93, 5, 0, NULL, 0, NULL, NULL),
(251, 93, 6, 0, NULL, 0, NULL, NULL),
(252, 6, 4, 6, '2017-05-31', 1, '2017-05-23 04:05:55', '2017-05-31 04:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_globals`
--

CREATE TABLE `tuition_globals` (
  `id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_globals`
--

INSERT INTO `tuition_globals` (`id`, `tuition_id`, `created_at`, `updated_at`) VALUES
(4, 69, '2017-06-02 04:43:34', '2017-06-02 04:43:34'),
(5, 70, '2017-06-02 04:43:34', '2017-06-02 04:43:34'),
(8, 57, '2017-06-02 05:03:30', '2017-06-02 05:03:30'),
(9, 58, '2017-06-02 05:03:30', '2017-06-02 05:03:30'),
(10, 65, '2017-06-02 05:03:30', '2017-06-02 05:03:30'),
(11, 66, '2017-06-02 05:03:30', '2017-06-02 05:03:30'),
(12, 67, '2017-06-02 05:03:31', '2017-06-02 05:03:31'),
(13, 68, '2017-06-02 05:03:31', '2017-06-02 05:03:31'),
(14, 71, '2017-06-02 05:03:31', '2017-06-02 05:03:31'),
(15, 72, '2017-06-02 05:03:31', '2017-06-02 05:03:31'),
(16, 77, '2017-06-02 05:04:05', '2017-06-02 05:04:05'),
(17, 78, '2017-06-02 05:04:05', '2017-06-02 05:04:05'),
(18, 79, '2017-06-02 05:04:05', '2017-06-02 05:04:05'),
(19, 80, '2017-06-02 05:04:05', '2017-06-02 05:04:05'),
(20, 81, '2017-06-02 05:04:05', '2017-06-02 05:04:05'),
(21, 82, '2017-06-02 05:04:05', '2017-06-02 05:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_history`
--

CREATE TABLE `tuition_history` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `tuition_detail_id` int(11) NOT NULL,
  `assign_date` date NOT NULL,
  `feedback_rating` varchar(20) NOT NULL,
  `feedback_comment` text NOT NULL,
  `tuition_fee` int(11) NOT NULL,
  `tuition_start_date` date NOT NULL,
  `tuition_end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_history`
--

INSERT INTO `tuition_history` (`id`, `teacher_id`, `tuition_detail_id`, `assign_date`, `feedback_rating`, `feedback_comment`, `tuition_fee`, `tuition_start_date`, `tuition_end_date`, `created_at`, `updated_at`) VALUES
(2, 7, 2, '2016-12-21', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-15 01:33:55', '2016-12-21 15:45:21'),
(3, 63, 2, '2017-01-26', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-15 01:34:10', '2017-01-26 12:03:46'),
(5, 7, 3, '2017-05-29', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-15 01:34:42', '2017-05-29 04:35:10'),
(6, 63, 3, '2016-11-18', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-15 01:35:04', '2016-11-17 23:46:51'),
(7, 59, 9, '2016-11-15', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-15 07:38:58', '2016-11-15 07:38:58'),
(8, 70, 29, '2016-11-22', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-22 05:37:33', '2016-11-22 05:37:54'),
(9, 63, 35, '2016-11-23', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-23 00:57:55', '2016-11-23 00:57:55'),
(10, 62, 38, '2016-11-24', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-24 04:33:39', '2016-11-24 04:34:47'),
(11, 62, 39, '2016-11-24', '', '', 0, '0000-00-00', '0000-00-00', '2016-11-24 05:21:15', '2016-11-24 05:21:26'),
(12, 123, 8, '2016-12-01', '', '', 0, '0000-00-00', '0000-00-00', '2016-12-01 18:27:37', '2016-12-01 18:27:49'),
(13, 123, 3, '2017-01-27', '', '', 0, '0000-00-00', '0000-00-00', '2016-12-09 18:09:49', '2017-01-27 00:50:12'),
(15, 59, 4, '2016-12-28', '', '', 0, '0000-00-00', '0000-00-00', '2016-12-28 11:30:06', '2016-12-28 11:30:06'),
(16, 58, 2, '2017-04-03', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-05 07:23:06', '2017-04-03 00:28:04'),
(17, 58, 3, '2017-04-03', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-05 07:23:06', '2017-04-03 00:28:14'),
(18, 68, 44, '2017-01-06', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-07 03:28:25', '2017-01-07 03:28:25'),
(20, 59, 43, '2017-03-01', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-13 00:17:58', '2017-03-01 01:04:15'),
(22, 63, 4, '2017-01-26', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-25 05:32:53', '2017-01-26 12:04:16'),
(25, 7, 51, '2017-01-26', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-26 11:49:02', '2017-01-26 11:49:17'),
(26, 63, 50, '2017-01-26', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-26 11:49:12', '2017-01-26 11:49:12'),
(27, 74, 49, '2017-01-31', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-31 01:31:12', '2017-01-31 01:31:12'),
(28, 74, 55, '2017-01-31', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-31 01:57:08', '2017-01-31 01:57:08'),
(29, 63, 54, '2017-01-31', '', '', 0, '0000-00-00', '0000-00-00', '2017-01-31 01:57:22', '2017-01-31 01:57:33'),
(31, 7, 53, '2017-02-13', '', '', 0, '0000-00-00', '0000-00-00', '2017-02-13 05:32:51', '2017-02-13 05:32:51'),
(38, 123, 90, '2017-03-01', '', '', 0, '0000-00-00', '0000-00-00', '2017-03-01 04:37:01', '2017-03-01 04:43:26'),
(39, 123, 91, '2017-03-01', '', '', 0, '0000-00-00', '0000-00-00', '2017-03-01 04:37:01', '2017-03-01 04:43:27'),
(44, 58, 184, '2017-03-22', '', '', 0, '0000-00-00', '0000-00-00', '2017-03-22 04:12:59', '2017-03-22 04:12:59'),
(45, 58, 186, '2017-03-22', '', '', 0, '0000-00-00', '0000-00-00', '2017-03-22 04:12:59', '2017-03-22 04:12:59'),
(46, 58, 181, '2017-04-03', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-03 04:02:10', '2017-04-03 04:02:50'),
(47, 6, 179, '2017-04-05', '', 'mark regular again', 0, '0000-00-00', '0000-00-00', '2017-04-05 04:12:19', '2017-06-01 01:13:41'),
(48, 59, 179, '2017-04-05', '', '', 1, '0000-00-00', '0000-00-00', '2017-04-05 04:21:14', '2017-04-05 05:01:26'),
(49, 61, 58, '2017-04-12', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-12 04:45:30', '2017-04-12 04:45:30'),
(50, 61, 198, '2017-04-12', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-12 04:45:30', '2017-04-12 04:45:30'),
(51, 68, 193, '2017-04-12', '', '', 1, '0000-00-00', '0000-00-00', '2017-04-12 05:05:18', '2017-04-12 09:15:50'),
(52, 63, 193, '2017-04-12', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-12 10:42:14', '2017-04-12 10:42:14'),
(53, 63, 194, '2017-04-12', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-12 10:43:31', '2017-04-12 10:43:31'),
(54, 63, 48, '2017-04-20', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-20 01:06:10', '2017-04-20 01:06:10'),
(55, 6, 56, '2017-04-24', '', 'mark regular', 0, '0000-00-00', '0000-00-00', '2017-04-24 00:52:42', '2017-06-01 01:13:18'),
(56, 7, 56, '2017-04-24', '', '', 0, '0000-00-00', '0000-00-00', '2017-04-24 00:53:12', '2017-04-24 00:53:12'),
(57, 6, 2, '2017-05-23', '', '', 1, '0000-00-00', '0000-00-00', '2017-05-23 02:44:54', '2017-05-23 04:02:10'),
(58, 6, 3, '2017-05-23', '', 'this tuition has been marked regular hhhhhhhhhhhhhhhhh  ggggg iiiiiiiiiiiiii', 0, '0000-00-00', '0000-00-00', '2017-05-23 02:46:14', '2017-06-01 01:22:48'),
(59, 6, 252, '2017-05-31', '', 'tt', 1, '0000-00-00', '0000-00-00', '2017-05-23 04:06:15', '2017-06-12 06:35:07'),
(60, 63, 82, '2017-06-06', '', '', 0, '0000-00-00', '0000-00-00', '2017-06-06 00:23:30', '2017-06-06 00:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_institute_preferences`
--

CREATE TABLE `tuition_institute_preferences` (
  `id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `institute_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_institute_preferences`
--

INSERT INTO `tuition_institute_preferences` (`id`, `tuition_id`, `institute_id`, `created_at`, `updated_at`) VALUES
(1, 58, 1, '2017-03-08 05:36:06', '2017-03-08 05:36:06'),
(2, 58, 3, '2017-03-08 05:36:06', '2017-03-08 05:36:06'),
(3, 58, 5, '2017-03-08 05:36:06', '2017-03-08 05:36:06'),
(4, 65, 3, '2017-03-08 07:15:51', '2017-03-08 07:15:51'),
(5, 67, 3, '2017-03-08 07:49:43', '2017-03-08 07:49:43'),
(6, 67, 4, '2017-03-08 07:49:43', '2017-03-08 07:49:43'),
(7, 68, 3, '2017-03-10 04:57:16', '2017-03-10 04:57:16'),
(8, 76, 1, '2017-03-10 05:45:22', '2017-03-10 05:45:22'),
(9, 76, 3, '2017-03-10 05:45:22', '2017-03-10 05:45:22'),
(10, 76, 5, '2017-03-10 05:45:22', '2017-03-10 05:45:22'),
(11, 66, 2, '2017-03-15 10:31:38', '2017-03-13 07:43:13'),
(12, 66, 5, '2017-03-15 10:31:42', '2017-03-13 07:43:13'),
(13, 78, 1, '2017-03-22 00:30:04', '2017-03-22 00:30:04'),
(14, 78, 2, '2017-03-22 00:30:04', '2017-03-22 00:30:04'),
(15, 79, 1, '2017-03-22 01:09:10', '2017-03-22 01:09:10'),
(16, 79, 2, '2017-03-22 01:09:10', '2017-03-22 01:09:10'),
(17, 79, 3, '2017-03-22 01:09:10', '2017-03-22 01:09:10'),
(18, 80, 3, '2017-03-22 08:35:51', '2017-03-22 08:35:51'),
(19, 80, 4, '2017-03-22 08:35:51', '2017-03-22 08:35:51'),
(20, 6, 1, '2017-03-30 01:22:35', '2017-03-30 01:22:35'),
(21, 6, 3, '2017-03-30 01:22:35', '2017-03-30 01:22:35'),
(22, 30, 1, '2017-03-30 01:25:47', '2017-03-30 01:25:47'),
(23, 30, 3, '2017-03-30 01:25:47', '2017-03-30 01:25:47'),
(24, 31, 1, '2017-03-30 01:26:50', '2017-03-30 01:26:50'),
(25, 37, 2, '2017-03-30 01:28:12', '2017-03-30 01:28:12'),
(26, 37, 3, '2017-03-30 01:28:12', '2017-03-30 01:28:12'),
(27, 37, 4, '2017-03-30 01:28:12', '2017-03-30 01:28:12'),
(28, 39, 2, '2017-03-30 01:30:24', '2017-03-30 01:30:24'),
(29, 39, 3, '2017-03-30 01:30:24', '2017-03-30 01:30:24'),
(30, 39, 4, '2017-03-30 01:30:24', '2017-03-30 01:30:24'),
(31, 50, 1, '2017-05-03 06:13:24', '2017-05-03 06:13:24'),
(32, 50, 3, '2017-05-03 06:13:24', '2017-05-03 06:13:24'),
(33, 50, 5, '2017-05-03 06:13:24', '2017-05-03 06:13:24'),
(34, 69, 1, '2017-05-16 06:51:49', '2017-05-16 06:51:49'),
(35, 90, 2, '2017-05-16 07:57:18', '2017-05-16 07:57:18'),
(36, 90, 3, '2017-05-16 07:57:18', '2017-05-16 07:57:18'),
(37, 91, 6, '2017-05-16 07:57:46', '2017-05-16 07:57:46'),
(38, 91, 7, '2017-05-16 07:57:46', '2017-05-16 07:57:46'),
(39, 92, 2, '2017-05-16 07:58:07', '2017-05-16 07:58:07'),
(40, 93, 1, '2017-05-16 07:58:22', '2017-05-16 07:58:22'),
(41, 81, 3, '2017-05-16 07:59:18', '2017-05-16 07:59:18'),
(42, 82, 2, '2017-05-16 08:00:36', '2017-05-16 08:00:36'),
(43, 83, 4, '2017-05-16 08:00:52', '2017-05-16 08:00:52'),
(44, 84, 5, '2017-05-16 08:01:05', '2017-05-16 08:01:05'),
(45, 57, 1, '2017-05-23 08:19:01', '2017-05-23 08:19:01'),
(46, 57, 2, '2017-05-23 08:19:01', '2017-05-23 08:19:01'),
(47, 57, 3, '2017-05-23 08:19:01', '2017-05-23 08:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `tuition_labels`
--

CREATE TABLE `tuition_labels` (
  `id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `tuition_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuition_labels`
--

INSERT INTO `tuition_labels` (`id`, `label_id`, `tuition_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2016-11-22 06:33:33', '2016-11-22 06:33:33'),
(2, 1, 1, '2016-11-22 06:33:33', '2016-11-22 06:33:33'),
(3, 2, 25, '2016-11-22 06:36:01', '2016-11-22 06:36:01'),
(4, 7, 25, '2016-11-22 06:36:01', '2016-11-22 06:36:01'),
(11, 4, 24, '2016-11-22 06:50:12', '2016-11-22 06:50:12'),
(12, 5, 24, '2016-11-22 06:50:12', '2016-11-22 06:50:12'),
(13, 1, 26, '2016-11-22 07:23:40', '2016-11-22 07:23:40'),
(14, 7, 26, '2016-11-22 07:23:40', '2016-11-22 07:23:40'),
(21, 3, 27, '2016-11-24 04:19:55', '2016-11-24 04:19:55'),
(22, 5, 27, '2016-11-24 04:19:55', '2016-11-24 04:19:55'),
(23, 4, 28, '2016-11-24 05:20:58', '2016-11-24 05:20:58'),
(24, 5, 29, '2016-11-28 02:29:23', '2016-11-28 02:29:23'),
(31, 4, 7, '2016-12-28 11:29:04', '2016-12-28 11:29:04'),
(32, 13, 7, '2016-12-28 11:29:04', '2016-12-28 11:29:04'),
(35, 10, 23, '2017-01-11 02:38:02', '2017-01-11 02:38:02'),
(48, 2, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(49, 4, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(50, 5, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(51, 7, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(52, 12, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(53, 13, 35, '2017-01-30 08:35:29', '2017-01-30 08:35:29'),
(101, 3, 51, '2017-02-13 13:18:37', '0000-00-00 00:00:00'),
(102, 5, 51, '2017-02-13 13:18:37', '0000-00-00 00:00:00'),
(106, 4, 54, '2017-02-27 12:12:32', '0000-00-00 00:00:00'),
(108, 3, 56, '2017-02-28 02:02:03', '2017-02-28 02:02:03'),
(113, 2, 60, '2017-03-01 02:22:32', '2017-03-01 02:22:32'),
(118, 3, 59, '2017-03-01 02:29:31', '2017-03-01 02:29:31'),
(119, 3, 61, '2017-03-01 02:30:54', '2017-03-01 02:30:54'),
(122, 2, 55, '2017-03-01 05:31:03', '2017-03-01 05:31:03'),
(203, 3, 70, '2017-03-08 15:58:16', '0000-00-00 00:00:00'),
(204, 7, 70, '2017-03-08 15:58:16', '0000-00-00 00:00:00'),
(206, 3, 71, '2017-03-08 16:00:04', '0000-00-00 00:00:00'),
(207, 7, 71, '2017-03-08 16:00:04', '0000-00-00 00:00:00'),
(209, 3, 72, '2017-03-08 16:05:12', '0000-00-00 00:00:00'),
(210, 7, 72, '2017-03-08 16:05:12', '0000-00-00 00:00:00'),
(213, 2, 74, '2017-03-09 08:39:24', '2017-03-09 08:39:24'),
(214, 4, 74, '2017-03-09 08:39:24', '2017-03-09 08:39:24'),
(221, 1, 58, '2017-03-10 01:38:19', '2017-03-10 01:38:19'),
(222, 2, 58, '2017-03-10 01:38:19', '2017-03-10 01:38:19'),
(223, 3, 58, '2017-03-10 01:38:19', '2017-03-10 01:38:19'),
(226, 3, 75, '2017-03-10 10:39:29', '0000-00-00 00:00:00'),
(227, 7, 75, '2017-03-10 10:39:29', '0000-00-00 00:00:00'),
(244, 2, 76, '2017-03-10 08:08:03', '2017-03-10 08:08:03'),
(245, 3, 76, '2017-03-10 08:08:03', '2017-03-10 08:08:03'),
(246, 7, 76, '2017-03-10 08:08:03', '2017-03-10 08:08:03'),
(309, 1, 78, '2017-03-22 00:30:04', '2017-03-22 00:30:04'),
(310, 2, 78, '2017-03-22 00:30:04', '2017-03-22 00:30:04'),
(311, 3, 78, '2017-03-22 00:30:04', '2017-03-22 00:30:04'),
(318, 2, 79, '2017-03-22 01:10:58', '2017-03-22 01:10:58'),
(319, 3, 79, '2017-03-22 01:10:58', '2017-03-22 01:10:58'),
(320, 4, 79, '2017-03-22 01:10:58', '2017-03-22 01:10:58'),
(329, 2, 80, '2017-03-22 08:39:01', '2017-03-22 08:39:01'),
(330, 5, 80, '2017-03-22 08:39:01', '2017-03-22 08:39:01'),
(339, 4, 31, '2017-03-30 01:26:50', '2017-03-30 01:26:50'),
(381, 1, 87, '2017-04-04 06:49:46', '0000-00-00 00:00:00'),
(382, 2, 87, '2017-04-04 06:49:46', '0000-00-00 00:00:00'),
(383, 3, 87, '2017-04-04 06:49:46', '0000-00-00 00:00:00'),
(384, 1, 88, '2017-04-04 06:49:52', '0000-00-00 00:00:00'),
(385, 2, 88, '2017-04-04 06:49:52', '0000-00-00 00:00:00'),
(386, 3, 88, '2017-04-04 06:49:52', '0000-00-00 00:00:00'),
(387, 1, 89, '2017-04-04 08:53:06', '0000-00-00 00:00:00'),
(388, 3, 89, '2017-04-04 08:53:06', '0000-00-00 00:00:00'),
(389, 7, 89, '2017-04-04 08:53:06', '0000-00-00 00:00:00'),
(396, 1, 66, '2017-04-11 00:57:28', '2017-04-11 00:57:28'),
(397, 3, 66, '2017-04-11 00:57:28', '2017-04-11 00:57:28'),
(398, 7, 66, '2017-04-11 00:57:28', '2017-04-11 00:57:28'),
(407, 2, 30, '2017-04-12 05:00:36', '2017-04-12 05:00:36'),
(408, 4, 30, '2017-04-12 05:00:36', '2017-04-12 05:00:36'),
(459, 5, 77, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(460, 7, 77, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(463, 5, 85, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(464, 7, 85, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(465, 5, 86, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(466, 7, 86, '2017-04-18 00:59:22', '2017-04-18 00:59:22'),
(470, 2, 0, '2017-04-18 02:42:19', '2017-04-18 02:42:19'),
(471, 1, 0, '2017-04-18 02:46:42', '2017-04-18 02:46:42'),
(481, 1, 52, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(482, 2, 52, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(483, 3, 52, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(484, 1, 53, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(485, 2, 53, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(486, 3, 53, '2017-04-18 03:59:01', '2017-04-18 03:59:01'),
(489, 4, 52, '2017-04-18 03:59:31', '2017-04-18 03:59:31'),
(495, 1, 33, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(496, 2, 33, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(497, 1, 34, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(498, 2, 34, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(499, 1, 36, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(500, 2, 36, '2017-04-18 04:58:51', '2017-04-18 04:58:51'),
(503, 3, 33, '2017-04-18 04:59:22', '2017-04-18 04:59:22'),
(504, 3, 34, '2017-04-18 04:59:22', '2017-04-18 04:59:22'),
(505, 3, 36, '2017-04-18 04:59:22', '2017-04-18 04:59:22'),
(511, 4, 33, '2017-04-19 07:41:07', '2017-04-19 07:41:07'),
(512, 5, 33, '2017-04-19 07:41:07', '2017-04-19 07:41:07'),
(513, 5, 34, '2017-04-19 08:11:29', '2017-04-19 08:11:29'),
(514, 5, 36, '2017-04-19 08:11:29', '2017-04-19 08:11:29'),
(531, 3, 40, '2017-05-03 04:34:10', '2017-05-03 04:34:10'),
(532, 5, 40, '2017-05-03 04:34:10', '2017-05-03 04:34:10'),
(538, 3, 42, '2017-05-03 06:00:51', '2017-05-03 06:00:51'),
(539, 5, 43, '2017-05-03 06:02:55', '2017-05-03 06:02:55'),
(540, 3, 45, '2017-05-03 06:04:55', '2017-05-03 06:04:55'),
(541, 5, 45, '2017-05-03 06:04:55', '2017-05-03 06:04:55'),
(542, 3, 46, '2017-05-03 06:05:42', '2017-05-03 06:05:42'),
(543, 5, 46, '2017-05-03 06:05:42', '2017-05-03 06:05:42'),
(544, 3, 47, '2017-05-03 06:06:44', '2017-05-03 06:06:44'),
(545, 5, 47, '2017-05-03 06:06:44', '2017-05-03 06:06:44'),
(546, 3, 48, '2017-05-03 06:07:49', '2017-05-03 06:07:49'),
(547, 5, 48, '2017-05-03 06:07:49', '2017-05-03 06:07:49'),
(548, 1, 49, '2017-05-03 06:12:01', '2017-05-03 06:12:01'),
(549, 2, 49, '2017-05-03 06:12:01', '2017-05-03 06:12:01'),
(550, 3, 49, '2017-05-03 06:12:01', '2017-05-03 06:12:01'),
(551, 4, 49, '2017-05-03 06:12:01', '2017-05-03 06:12:01'),
(552, 3, 50, '2017-05-03 06:13:24', '2017-05-03 06:13:24'),
(553, 5, 50, '2017-05-03 06:13:24', '2017-05-03 06:13:24'),
(554, 4, 37, '2017-05-05 00:38:14', '2017-05-05 00:38:14'),
(555, 4, 38, '2017-05-05 00:38:14', '2017-05-05 00:38:14'),
(556, 4, 39, '2017-05-05 00:38:14', '2017-05-05 00:38:14'),
(557, 1, 37, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(558, 2, 37, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(559, 3, 37, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(560, 1, 38, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(561, 2, 38, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(562, 3, 38, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(563, 1, 39, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(564, 2, 39, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(565, 3, 39, '2017-05-05 00:39:00', '2017-05-05 00:39:00'),
(567, 3, 69, '2017-05-16 06:51:49', '2017-05-16 06:51:49'),
(568, 7, 69, '2017-05-16 06:51:49', '2017-05-16 06:51:49'),
(569, 1, 90, '2017-05-16 07:57:18', '2017-05-16 07:57:18'),
(570, 3, 90, '2017-05-16 07:57:18', '2017-05-16 07:57:18'),
(571, 7, 90, '2017-05-16 07:57:18', '2017-05-16 07:57:18'),
(572, 5, 93, '2017-05-16 07:58:22', '2017-05-16 07:58:22'),
(573, 1, 81, '2017-05-16 07:59:18', '2017-05-16 07:59:18'),
(574, 2, 81, '2017-05-16 07:59:18', '2017-05-16 07:59:18'),
(575, 3, 81, '2017-05-16 07:59:18', '2017-05-16 07:59:18'),
(576, 5, 82, '2017-05-16 08:00:36', '2017-05-16 08:00:36'),
(577, 7, 82, '2017-05-16 08:00:36', '2017-05-16 08:00:36'),
(578, 1, 83, '2017-05-16 08:00:52', '2017-05-16 08:00:52'),
(579, 2, 83, '2017-05-16 08:00:52', '2017-05-16 08:00:52'),
(580, 3, 83, '2017-05-16 08:00:52', '2017-05-16 08:00:52'),
(581, 1, 84, '2017-05-16 08:01:05', '2017-05-16 08:01:05'),
(582, 2, 84, '2017-05-16 08:01:05', '2017-05-16 08:01:05'),
(583, 3, 84, '2017-05-16 08:01:05', '2017-05-16 08:01:05'),
(588, 1, 65, '2017-05-17 01:19:07', '2017-05-17 01:19:07'),
(589, 2, 65, '2017-05-17 01:19:07', '2017-05-17 01:19:07'),
(590, 3, 65, '2017-05-17 01:19:07', '2017-05-17 01:19:07'),
(591, 4, 65, '2017-05-17 01:19:07', '2017-05-17 01:19:07'),
(603, 1, 57, '2017-05-23 08:19:01', '2017-05-23 08:19:01'),
(604, 2, 57, '2017-05-23 08:19:01', '2017-05-23 08:19:01'),
(605, 3, 57, '2017-05-23 08:19:01', '2017-05-23 08:19:01'),
(614, 2, 6, '2017-06-12 04:24:59', '2017-06-12 04:24:59'),
(615, 4, 6, '2017-06-12 04:24:59', '2017-06-12 04:24:59'),
(616, 12, 6, '2017-06-12 04:24:59', '2017-06-12 04:24:59'),
(617, 13, 6, '2017-06-12 04:24:59', '2017-06-12 04:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `tution_status`
--

CREATE TABLE `tution_status` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tution_status`
--

INSERT INTO `tution_status` (`id`, `name`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Trial', '#ea0707', '2016-10-28 05:53:40', '2017-05-23 00:27:35'),
(2, 'Regular', '#54c60d', '2016-10-28 00:54:23', '2017-03-01 00:53:45'),
(3, 'Complete', '#f2db11', '2016-10-28 00:54:46', '2017-03-02 01:16:01'),
(4, 'Trial Failed', '#407ae8', '2016-10-29 01:14:49', '2017-03-02 01:26:22'),
(5, 'Other', '#e5a504', '2016-11-24 05:10:39', '2017-03-02 01:17:00'),
(6, 'test', '#995555', '2017-02-28 07:48:43', '2017-02-28 07:48:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `confirmation_code`, `confirmed`, `created_at`, `updated_at`) VALUES
(1, 'javed afaq', 'admin@admin.com', '$2y$10$Vzzh5IWzacU.b2aml9eO4eA/O43u.4WGm5BVdBu3IGg2ogG8bh0h.', 'TtQlR3cvG8Hgl0dtLOlJDkADejd284kz4q5VckntCQZSe3Y7NF9HYoAAol2P', '', 1, '2016-10-03 19:00:00', '2017-06-22 01:48:43'),
(2, ' ', 'st@st.com', '$2y$10$r2ojsnkFz7274fiemu183eD4j955SFvv3ETKH2EXDBD9fOgIgxHRy', 'D3INT9sn176eUrjm5xhWH7bn7EppvtnXixu36JSBgVzhcWXroFxkU7bNB3Us', '', 1, NULL, '2017-04-24 01:08:50'),
(3, 'teacher', 'tch@tch.com', '$2y$10$uxM8TjsOLNcXJuK0.QGW/ugj0PPAI/O2lnGwrBXewx8L5GPTtY5S6', 'gJzxRR8vvuuXlfSYl5JFIaosmwTZidZHVjda2JBdIUR0cyaR3R9kpMI9mMrR', '', 0, NULL, '2016-11-23 04:11:24'),
(47, 'muhammad yousaf', 'umer@gmail.com', '$2y$10$yPpOom5PMKCnt0IPJhicWeNMafq2lA7ujVhSHYvPiwnp8SAe8M/JG', NULL, '', 1, '2016-10-18 07:14:21', '2017-01-25 07:07:03'),
(48, 'wasim anjum', 'anjum@anjum.com', '$2y$10$BxTlR.qtjemQkCfMv0ZP4.ICSAKkbb8ATXNugBdThLX64QdvEtEJm', 'rSAD2UICk8EWXwl3Wht2zwmRfMjweXUPufPMjWo4wRd0e806H67toUqlOVfd', '', 1, '2016-10-19 01:44:08', '2017-05-25 08:14:57'),
(49, 'Amanullah Jamil', 'jwanjum@gmail.com', '$2y$10$qpmW7ixSHmpOknyr3.QzAu4mZbggnwJ2qGW30gcx/gH1Ffgm0FNy2', 'Et9y1AxovClTr4lgXu9lDQMoCIBwJKFyl6GWLjZtmkl259zHwPAp1fr52xym', '', 1, '2016-10-19 01:45:30', '2017-06-22 21:08:46'),
(50, 'Nasir Hussain', 'afaq@gmail.com', '$2y$10$IFasJnvxCXWQJa84uj4ieewsQSZ0YDptTLiSwqbUlKhkSZQ3lpTfK', NULL, '', 1, '2016-10-19 01:47:38', '2017-05-23 08:21:41'),
(51, 'Ahmed Ali', 'ahmed@hassan.com', '$2y$10$YBvB.pnPHqaKQkPRM6TVnOYh6oVna8pRMB/ew1ZI/47watbI80RFO', NULL, '', 1, '2016-10-22 08:22:31', '2017-05-23 08:26:06'),
(52, 'reyan ahmed', 'reyan@reyan.com', '$2y$10$z1t4APa7xIJJ9fIJKHE8OObV0cskNKZ90uJLZgZOyEwgANdgVQ.i2', NULL, '', 1, '2016-10-22 08:25:13', '2017-04-12 04:44:10'),
(53, 'umair ali', 'umair@gmail.com', '$2y$10$2RmMd6TGP2ggtLSI7gA8Mu2a9PJNvW7EbDjY8glxy6XKqy6D/YtyG', NULL, '', 0, '2016-10-22 08:26:59', '2016-10-22 08:26:59'),
(54, 'Nauman Ahmed', 'nauman@gmail.com', '$2y$10$sQVT3UrTtvmFAPvMn/5wMe73beEop2YLtnCmzBuPWK3glaQ7MWiem', NULL, '', 1, '2016-10-22 08:29:11', '2017-05-23 08:27:51'),
(55, 'umer javed', 'jw@gmail.com', '$2y$10$hRVKRN3z1K8lVWXlCW2deOCBtqJR0AJYdZjQpG1DuMrIke7w29VMO', NULL, '', 0, '2016-10-22 08:34:04', '2016-10-22 08:34:04'),
(56, 'Junaid Ahmed', 'ajax@admin.com', '$2y$10$Ga7ed3qDBl49qOsVO2Es4.v3W/NI6Eap9Rqo7eTNzvDiTeR7c7vaa', NULL, '', 0, '2016-10-24 03:44:33', '2017-05-23 08:29:53'),
(57, 'ajax test', 'ajax01@admin.com', '$2y$10$nPGHasjK4TyKV7r/2bwkA.ig7cHFDWmfr.7S96NpKOHtW71Unod1i', NULL, '', 0, '2016-10-24 03:46:38', '2016-10-24 03:46:38'),
(58, 'test ajax', 'test@ajax.com', '$2y$10$cTTEvWmyL/cz6lZGkyjkXel4TurXSjyyhW0ZymY725LFSBlYsUMIu', NULL, '', 0, '2016-10-24 03:48:48', '2016-10-24 03:48:48'),
(59, 'Bilal', 'bilal@gmail.com', '$2y$10$K5064oH.dtpD1GXS45Lxdujduf9CSvlAHCb/JxpmbtAGoBbhCBjyy', NULL, '', 1, '2016-10-24 06:51:30', '2017-06-12 04:28:00'),
(60, 'madni shukari', 'madni@gmail.com', '$2y$10$5X5jFkVsayQF.ir8xPX/ZuKKLdfmTtdobqKMCiRqrnBWl0mCWlSX6', NULL, '', 0, '2016-10-24 06:54:42', '2016-10-24 06:54:42'),
(61, 'rahat ali', 'rahat@ali.com', '$2y$10$j4NTn.aicQn4gZ4ktpsmX.mMxbzymS9X5vwqsHEmahj/VUaLqE/n2', NULL, '', 0, '2016-10-24 06:56:10', '2016-10-24 06:56:10'),
(62, 'Fahad Shami', 'zeeshan@gmail.com', '$2y$10$Qf9T43qHY3z7zdCNfT813Ow6MNzRP8YMs5V0wFhhnrey423PfSoQO', NULL, '', 0, '2016-10-24 08:48:46', '2017-05-15 06:33:32'),
(63, 'zafar', 'zafar@zafar.com', '$2y$10$iDVkHDwl8t8BIsqWR.9BPeMEgiDRwvvjKUdCLR.0kd2I8VJA2aFVq', NULL, '', 0, '2016-10-25 00:25:05', '2017-06-06 01:34:10'),
(64, 'hasan jamil', 'mohsin@mohsin.com', '$2y$10$4Thy9WtMLhT4i4s8r9Yp/uVcjQV5cmBjgiALKkGvwzuSG9WlV4ZnC', NULL, '', 0, '2016-10-25 01:25:51', '2017-05-15 06:33:54'),
(65, 'Farrukh Nadeem', 'farrukh@admin.com', '$2y$10$uIzP3gUVoEy5jX0GPyyRKOTh9kRgc042OeRLGPZQmUazu9Zfmqbia', NULL, '', 1, '2016-10-25 01:26:51', '2017-06-08 02:31:14'),
(66, 'Tayyab', 'tayyab@admin.com', '$2y$10$zm9EcZcm0mOV1eX3U8B/TuRRSLnvvWd8XmgbREXVe//iqhnwMJi2a', NULL, '', 0, '2016-10-25 01:28:06', '2017-06-07 00:28:23'),
(67, 'irfan jamil', 'aman@gmail.com', '$2y$10$4ITBhXpV5XhWOeBiFpSvXeBn9ECThpzIaMAaAKnUhKKnuvDEfEfsO', NULL, '', 1, '2016-10-25 06:54:58', '2017-06-08 02:47:28'),
(68, 'naeem iqbal', 'naeem@gmail.com', '$2y$10$16s/RFBC3aYby.bUfMC7POd6S7PSlWXt7uKR1Nztpbuf5mgain18G', NULL, '', 0, '2016-10-25 07:02:39', '2017-06-08 02:47:53'),
(69, 'javed anjum', 'test11@test11.com', '$2y$10$CCwjdiVHXtPdKP1//yIFFuAPPTL01.MSMWLtlyZSdy5ixdDWSXRYy', NULL, '', 0, '2016-10-25 08:11:56', '2017-05-15 06:34:17'),
(70, 'test11', 'test11@test.com', '$2y$10$SnFFn2usP/2DBKNgrhx8OOsAd5HrD1Hf88RHyMY0Tpwq9E6F/UTlC', NULL, '', 0, '2016-10-25 08:14:46', '2017-06-06 07:03:39'),
(73, 'janglos khan', 'janglos@gmail.com', '$2y$10$PSmaqGaRi0OpP4KIZICQQeb/IBiO3u7BFFHHYwQzaTjXPRJMXQE.u', NULL, '', 0, '2016-10-29 06:26:49', '2016-10-29 06:26:49'),
(74, 'asad umer', 'asad@umer.com', '$2y$10$.Hl2tboPN3UyhqjdY8Muk.G9u5IKTlon66lXGrKKJtJUmtwpz1rua', NULL, '', 0, '2016-10-29 09:01:59', '2017-01-26 02:13:16'),
(75, 'Momin Ullah', 'changez@khan.com', '$2y$10$l1AiaFiJEEhWesCYa7wT7uI3jOoVQplL.gzIX2klc1oxh4NdT/JKq', NULL, '', 0, '2016-10-29 09:25:13', '2017-05-15 06:34:42'),
(76, 'idress lala', 'idress@idress.com', '$2y$10$X3LtNKbIw5VpnMWwIwLuteS67cJV4.OMvot7CsFSjJCcrbNFXnD/W', NULL, '', 0, '2016-10-29 09:28:30', '2016-10-29 09:28:30'),
(77, 'azhar mahmood', 'azharmehmood@azhar.com', '$2y$10$s7lDwVRi/HKDS4DvtAoyWuMQ0P/92/8PNSGnVNKevgtsTGNqgFJAS', NULL, '', 0, '2016-10-29 09:30:04', '2016-10-29 09:30:04'),
(82, 'haider ali', 'haider@admin.com', '$2y$10$aKuidkKjEWSw4ZVTQ5xg4e4cFrSgJT9KZINdpAWBrPRDnzYgGXCXW', NULL, '', 0, '2016-10-29 10:51:13', '2016-10-29 10:51:13'),
(83, 'test01 khan', 'teat01@admin.com', '$2y$10$5d.3WVuXFUg4V8yaqlF9MuqngqZdWW5JBFr5sWhLVRZBNwhD1V9tm', NULL, '', 0, '2016-10-29 10:53:46', '2016-10-29 10:53:46'),
(88, 'test02 test02', 'victor@test.com', '$2y$10$RhbYKw2f8DiT/LSpcVcmGOzGYITH.2ZlBiT9qxdd8F702WiVBxH0.', NULL, '', 0, '2016-10-29 11:23:59', '2016-10-29 11:23:59'),
(89, 'mask test', 'mask@admin.com', '$2y$10$Xa27Zy.X4knugnqvgvm5O.BVpSTMQyZJr5Lt.joF8UG2RiRMsmp8u', NULL, '', 0, '2016-10-31 05:13:23', '2016-10-31 05:13:23'),
(94, 'warning modal', 'warning@admin.com', '$2y$10$CjvmAk60yS3W4vUDB.Lp/un7CtsYMSVnYaLpf4.IZXgH.T5aXFEdS', NULL, '', 0, '2016-10-31 08:30:23', '2016-10-31 08:30:23'),
(95, 'test01 test01', 'test01@test01.com', '$2y$10$gXkVxhU184wgcN/u2LJz3OM7gcVitVuXYIugQ9VWlj7YemKhAPm2K', NULL, '', 0, '2016-10-31 08:32:12', '2016-10-31 08:32:12'),
(96, 'teacher labels', 'teacher@labels.com', '$2y$10$pKaqwmf5tXEuMnwfERYRU.w.9SwhUf/V5nrz2nW9pxyTkl6jeq7SK', NULL, '', 0, '2016-11-21 08:09:22', '2016-11-21 08:09:22'),
(97, 'confrim password', 'confirm@password.com', '$2y$10$0oVJrrgKJwXxg9jVd7tbLOsIPTCSgk4KVZin7iGV9m.02H0V58cMK', NULL, '', 0, '2016-11-23 05:41:16', '2016-11-23 05:41:16'),
(100, 'html5 password', 'html5@password.com', '$2y$10$8f3SUBmQhT3Q6bjLohSFBO9/ugjnnh.r/lGhlS8Sw6qAz/15E1fLG', NULL, '', 0, '2016-11-23 06:35:18', '2016-11-23 06:35:18'),
(101, 'sdfsdf sdfsdf', 'admin@admidfsdfn.com', '$2y$10$415fyJTxoyv9dKygW/OhjOrBsUr.34wrvjVlNnbfW/j0bbgeCd96e', NULL, '', 0, '2016-11-23 09:40:03', '2016-11-23 09:40:03'),
(102, 'test error', 'test@essir.com', '$2y$10$kLrr3Wo7US2RVJOslCoDhul9/VfnHl/XcoNc5lKkk/TsDeroTZ5te', NULL, '', 0, '2016-11-23 09:44:52', '2016-11-23 09:44:52'),
(103, 'test error', 'test@test.com', '$2y$10$hr3JO1zYzHhxOOMAelrhaOW6b7HJpFanmBJI9/n.qnGTO.L2TQCuu', NULL, '', 0, '2016-11-23 09:50:12', '2016-11-23 09:50:12'),
(104, 'test error', 'test@error.com', '$2y$10$TtIs7j83oGAUer10R2zfMeoIp2F1kzSfbbPnViYFYggNPRHXRtPnO', NULL, '', 0, '2016-11-23 09:53:12', '2016-11-23 09:53:12'),
(105, 'test test', 'test@testerror.com', '$2y$10$OJ0B7UAqQh1lI0jnFk2Y1O0YMSFkQDhZxBK3zqxw.UHHka9DKOZpu', NULL, '', 0, '2016-11-23 09:56:16', '2016-11-23 09:56:16'),
(106, 'Nabeel Khan', 'test@testerrorss.com', '$2y$10$lTxkGHuf22P1r5IVVxhooe70E.17s6p1KbyQ4DqeOPyVCn6lwNbia', NULL, '', 1, '2016-11-23 09:57:23', '2017-01-26 02:23:28'),
(108, 'email validate', 'email@validate.com', '$2y$10$uYQ6ZQwm8Tjbffw3dCPaP.zx.e4FWtQsiw9a.huVPRVtsmSPeqgiG', NULL, '', 0, '2016-11-23 14:19:32', '2016-11-23 14:19:32'),
(109, 'url test', 'url@test.com', '$2y$10$Q3.vpnWgjhjrTNFnMuxfze1fBXWLmfGkAmwm2KEcxS5hzdGTke9RS', NULL, '', 0, '2016-11-24 01:33:47', '2016-11-24 01:33:47'),
(110, 'save with model', 'jwaseem_test@thinkdonesolutions.com', '$2y$10$X6SPk32WSa.a7YvnZbg/0eVedEZHHjroh/WzoerAZeZMVDWSnzIGq', NULL, '', 0, '2016-11-24 02:01:43', '2016-11-24 02:01:43'),
(111, 'test user', 'test@user.com', '$2y$10$PSy6RG6TMHCzpEIq5I1NueEC.iHd8bQp52zikSC34MtQlKuqyBtni', NULL, '', 1, '2016-11-28 02:49:49', '2016-11-28 04:04:53'),
(116, 'confirm code', 'confirm@code.com', '$2y$10$Wus907ri45xa1xsDQhLFseBgNyOpUTEJCrkp2eJw8FEEr9cjBQ/SS', NULL, '', 1, '2016-11-28 05:27:38', '2016-11-28 05:46:02'),
(128, 'admin user', 'admin@user.com', '$2y$10$xX0qT0wd.lfVLMWWBsIHFuWLOVaolXtaOLHdtfORBfqbY9q7ORdkq', '1wXQ7WTBbIdJ8P5j2xhXvQ3rUarBGJWMueWjwH4Cwi5clZajDPr2QVuAKAA9', '', 1, '2016-11-29 04:31:08', '2016-11-29 05:48:23'),
(147, 'salman azim', 'salman_az@gmail.com', '$2y$10$C9pJ2ylBXt4Mt/GbA2sPleVnOWEMSZ4qMk2dMpwQGCsQBbwIBUur.', 'hZ88XdTds7D2hLSZDGGsBXEG7eHj9TETJfJSK1mxitmmVZtBUE5RsNzUu9Py', '', 1, '2016-11-29 09:21:53', '2016-11-29 09:32:26'),
(148, 'yousaf javed', 'jwanjum@yahoo.com', '$2y$10$4MXvejjEucqWm9qGMiS0XeEIm.4mnz0icLuSjWT4ZhxatwmHv/8w2', 'zLt820vFjYYAXFOt75jQDk474QaAFgAFFeFkdikIepB6rFHvQnhQUVCuBcwL', '', 1, '2016-11-29 09:40:05', '2017-06-08 02:48:25'),
(150, 'junaid ahmed', 'junaid6095@gmail.com', '$2y$10$5sD6.POHBcXVkWmu5h.lsuPbvMcGf.OVyGbezfSzv68rSvRAWHk12', 'gBvN0CaiDKVBxetJhFJvG44QE59hva6HBTYyO6w6Ch3S3bObqoFC8w1YxvjO', '', 1, '2016-11-30 04:19:54', '2016-11-30 23:57:53'),
(152, 'live test', 'live@test.com', '$2y$10$p.dXyQB8J7O9CCS/WRVdHOJ.jkCeO92YP14fG7jaAu3q9bIaw2NZu', NULL, '', 1, '2016-12-01 17:32:27', '2016-12-01 17:32:27'),
(153, 'Tayyab Arif', 'tayyabarifsheikh@yahoo.com', '$2y$10$3cNOBNxb8aX2ZgQexy/Bwu9ArJ47r4r5ZZLyiTho1BA4lxfOBzGuu', NULL, '', 1, '2016-12-01 19:07:20', '2016-12-01 19:07:20'),
(154, 'younus khan', 'younus@younus.com', '$2y$10$PR14mRnUxyq/VvEKP2583.SENpyg18VUdlHWBRguN9i5rVNLu2gAW', NULL, '', 1, '2017-01-05 04:23:05', '2017-01-05 04:23:05'),
(156, 'new register', 'new@register.com', '$2y$10$XiKiKXMqfJu3519EaB9CEefoO3GfzvceBNIVvNUsSXE53hII38l9q', 'L7s76AhXk8lO7SIjPdwtkMJTjpal1etUSJWbstdMiPl0HFhhE4H8qNp4IYqa', '', 1, '2017-01-25 00:47:27', '2017-01-26 04:33:23'),
(157, 'is active', 'is@active.com', '$2y$10$SzMpvrk6FdGh6wCk16Igo.g.YcGBpgVgV6UfWzrOjRhr4PVTzNSiC', NULL, '', 0, '2017-02-28 03:58:21', '2017-02-28 03:58:54'),
(158, 'tuition category', 'tuition@category.com', '$2y$10$Gh4Sko5M56wwmyOT5TMrd.iuhwb8MR59ZPn3OffO7nPNkrNcMIbWm', NULL, '', 1, '2017-03-06 07:15:59', '2017-03-06 07:28:55'),
(160, 'preferred institute', 'preferred@institute', '$2y$10$w/NouUlcjFJ17o4ri6qATOosdt6kmWKtHmOeO9RjCf6NPUp4zgOHK', NULL, '', 1, '2017-03-07 05:32:05', '2017-03-07 05:35:55'),
(167, ' ', 'fahad@shami.com', '$2y$10$tIqoJ9LvhjJK4akqrUjk0OuZgZZ1qWTIAnyISLYySI7ndFH534UoK', NULL, '', 1, '2017-04-20 08:26:06', '2017-04-20 08:32:31'),
(168, 'hasan jamil', 'hasan@jamil.com', '$2y$10$6XagREbBJeZkrs5NVBaFDOP5Y4oeMUlNWfrB4wOubfi.hWCU6Rpam', NULL, '', 1, '2017-04-20 08:33:50', '2017-04-20 08:33:50'),
(171, 'new teacher', 'new@teacher.com', '$2y$10$2XIWrgbsczl5Q3QBwzglHO0pFFXOkh0gCEaDI3mD3h71Gvij/qp6i', NULL, '', 1, '2017-04-23 23:58:13', '2017-04-23 23:58:13'),
(188, 'Irfan Mughal', 'irfan@mughal.com', '$2y$10$IEKB3dxvXr65Jm0b0Xdol.UV8rgQNE7of0I3cMkQ.tOR6S2cU.c6a', 'uaxWT2fQDk17t1EAiuig5vCiGk1I1oDF9NTrc9wRqrZqRY6d6zf39QQvQGTw', '', 1, '2017-05-04 02:47:51', '2017-05-04 02:50:47'),
(189, 'Momin', 'momin@momin.com', '$2y$10$xB.iVR3Dp0GKvMtIg9oxB.V7vkRIaKtfePABsgD2.ldzqmBfR8XrG', NULL, '', 1, '2017-05-15 01:27:05', '2017-05-15 01:27:05'),
(196, 'zain ul abidin', 'zain@gmail.com', '$2y$10$x3sezMsjAhLu4dcWCd4y6.93gUGNkePX5zRWtuFzSfRkFaOiTi45q', 'ARYEdBnXRDBzFDmyOYhE061C0Ya3TousvNDwHSinXqDzEtXKDTJi749nM7fS', '', 1, '2017-06-13 06:33:45', '2017-06-13 06:34:25'),
(197, 'Ali Ahmed', 'ali@gmail.com', '$2y$10$60ZWGgOxkXGdGIgsCvaedeVh7wxokzHqW7VKbtJy3g06slba6ZuBa', 'SuXCsBOiR4QY5nlPUVX5LouVEOsxYb0UZAykdKbQAd8REpfL9Q36YhBugY1W', '', 1, '2017-06-13 06:48:11', '2017-06-13 06:54:05'),
(198, 'muhammad yousaf', 'yousaf@gmail.com', '$2y$10$L7GcATgBEYTW6E3wTNDOHuIIVt2gcAXlZZcbs5z6F.RLR8GS0EGTO', 'hBQe6dR8uKV7S6z90JkTAxkUIHVDOcvwRM783GvAQKoaoZU5nLupFSTuM4pZ', '', 1, '2017-06-15 02:35:10', '2017-06-29 05:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permissions`
--

CREATE TABLE `user_has_permissions` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_has_permissions`
--

INSERT INTO `user_has_permissions` (`user_id`, `permission_id`) VALUES
(1, 9),
(2, 10),
(3, 11),
(47, 11),
(48, 11),
(49, 11),
(50, 11),
(51, 11),
(52, 11),
(53, 11),
(54, 11),
(55, 11),
(56, 11),
(57, 11),
(58, 11),
(59, 11),
(60, 11),
(61, 11),
(62, 11),
(63, 11),
(64, 11),
(65, 11),
(66, 11),
(67, 11),
(68, 11),
(69, 11),
(70, 11),
(73, 11),
(74, 11),
(75, 11),
(76, 11),
(77, 11),
(82, 11),
(83, 11),
(89, 11),
(94, 11),
(95, 11),
(96, 11),
(97, 11),
(100, 11),
(101, 11),
(102, 11),
(103, 11),
(104, 11),
(105, 11),
(106, 11),
(108, 11),
(109, 11),
(110, 11),
(111, 11),
(116, 11),
(128, 11),
(147, 11),
(148, 11),
(150, 11),
(152, 11),
(153, 11),
(154, 11),
(156, 9),
(157, 11),
(158, 11),
(160, 11),
(167, 11),
(168, 11),
(171, 11),
(188, 11),
(189, 11),
(196, 10),
(197, 10),
(198, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user_has_roles`
--

CREATE TABLE `user_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_has_roles`
--

INSERT INTO `user_has_roles` (`role_id`, `user_id`) VALUES
(12, 1),
(12, 156),
(13, 2),
(13, 196),
(13, 197),
(13, 198),
(14, 3),
(14, 47),
(14, 48),
(14, 49),
(14, 50),
(14, 51),
(14, 52),
(14, 53),
(14, 54),
(14, 55),
(14, 56),
(14, 57),
(14, 58),
(14, 59),
(14, 60),
(14, 61),
(14, 62),
(14, 63),
(14, 64),
(14, 65),
(14, 66),
(14, 67),
(14, 68),
(14, 69),
(14, 70),
(14, 73),
(14, 74),
(14, 75),
(14, 76),
(14, 77),
(14, 82),
(14, 83),
(14, 89),
(14, 94),
(14, 95),
(14, 96),
(14, 97),
(14, 100),
(14, 101),
(14, 102),
(14, 103),
(14, 104),
(14, 105),
(14, 106),
(14, 108),
(14, 109),
(14, 110),
(14, 111),
(14, 116),
(14, 128),
(14, 147),
(14, 148),
(14, 150),
(14, 152),
(14, 153),
(14, 154),
(14, 157),
(14, 158),
(14, 160),
(14, 167),
(14, 168),
(14, 171),
(14, 188),
(14, 189);

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Zone1', 'This is about Zone One 1', '2017-05-23 05:02:17', '2017-05-23 00:02:17'),
(2, 'Zone2', '', '2017-04-04 09:01:00', '0000-00-00 00:00:00'),
(3, 'Zone3', '', '2017-04-04 11:01:50', '2017-04-04 06:01:50'),
(4, 'Zone4', '', '2017-04-04 06:01:42', '2017-04-04 06:01:42'),
(5, 'zone5', '', '2017-04-04 06:02:04', '2017-04-04 06:02:04'),
(6, 'zone6', '', '2017-04-04 06:02:15', '2017-04-04 06:02:15'),
(7, 'zone7', '', '2017-04-04 06:02:27', '2017-04-04 06:02:27'),
(8, 'zone8', '', '2017-04-04 06:02:36', '2017-04-04 06:02:36'),
(9, 'zone9', '', '2017-04-04 06:02:46', '2017-04-04 06:02:46'),
(10, 'zone10', '', '2017-04-04 06:02:55', '2017-04-04 06:02:55'),
(11, 'zone11', '', '2017-04-04 06:03:14', '2017-04-04 06:03:14'),
(12, 'zone12', '', '2017-04-04 06:06:01', '2017-04-04 06:06:01'),
(13, 'zone13', '', '2017-04-04 11:09:16', '2017-04-04 06:09:16'),
(14, 'Zone14', 'This about zone14', '2017-04-14 07:35:04', '2017-04-14 07:35:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_status`
--
ALTER TABLE `application_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subject_mappings`
--
ALTER TABLE `class_subject_mappings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_notes`
--
ALTER TABLE `global_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutes`
--
ALTER TABLE `institutes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marital_status`
--
ALTER TABLE `marital_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrers`
--
ALTER TABLE `referrers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `special_notes`
--
ALTER TABLE `special_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id_idx` (`gender_id`),
  ADD KEY `uid` (`user_id`),
  ADD KEY `uid_2` (`user_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gender_id_idx` (`gender_id`),
  ADD KEY `marital_id_idx` (`marital_status_id`),
  ADD KEY `teacher_band_id_idx` (`teacher_band_id`),
  ADD KEY `user_id_idx` (`user_id`);

--
-- Indexes for table `teacher_applications`
--
ALTER TABLE `teacher_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_bands`
--
ALTER TABLE `teacher_bands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_bookmarks`
--
ALTER TABLE `teacher_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tuition_detail_id_idx` (`tuition_id`),
  ADD KEY `teacher_bands_idx` (`teacher_id`);

--
-- Indexes for table `teacher_globals`
--
ALTER TABLE `teacher_globals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_institute_preferences`
--
ALTER TABLE `teacher_institute_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_labels`
--
ALTER TABLE `teacher_labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_location_preferences`
--
ALTER TABLE `teacher_location_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `teacher_qualifications`
--
ALTER TABLE `teacher_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id_idx` (`teacher_id`);

--
-- Indexes for table `teacher_subject_preferences`
--
ALTER TABLE `teacher_subject_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id_idx` (`teacher_id`),
  ADD KEY `teacher_idp_idx` (`teacher_id`);

--
-- Indexes for table `teacher_tuition_categories`
--
ALTER TABLE `teacher_tuition_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tlabels`
--
ALTER TABLE `tlabels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuitions`
--
ALTER TABLE `tuitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `std_id_idx` (`student_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `tuition_assignment_status`
--
ALTER TABLE `tuition_assignment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuition_categories`
--
ALTER TABLE `tuition_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuition_details`
--
ALTER TABLE `tuition_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid_idx` (`tuition_id`),
  ADD KEY `class_subject_mapping_id` (`class_subject_mapping_id`);

--
-- Indexes for table `tuition_globals`
--
ALTER TABLE `tuition_globals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuition_history`
--
ALTER TABLE `tuition_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teach_id_idx` (`teacher_id`);

--
-- Indexes for table `tuition_institute_preferences`
--
ALTER TABLE `tuition_institute_preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tuition_labels`
--
ALTER TABLE `tuition_labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tution_status`
--
ALTER TABLE `tution_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_has_permissions`
--
ALTER TABLE `user_has_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `user_has_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `user_has_roles_user_id_foreign` (`user_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_status`
--
ALTER TABLE `application_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;
--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `class_subject_mappings`
--
ALTER TABLE `class_subject_mappings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `global_notes`
--
ALTER TABLE `global_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `institutes`
--
ALTER TABLE `institutes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `marital_status`
--
ALTER TABLE `marital_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `referrers`
--
ALTER TABLE `referrers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `special_notes`
--
ALTER TABLE `special_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT for table `teacher_applications`
--
ALTER TABLE `teacher_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `teacher_bands`
--
ALTER TABLE `teacher_bands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `teacher_bookmarks`
--
ALTER TABLE `teacher_bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;
--
-- AUTO_INCREMENT for table `teacher_globals`
--
ALTER TABLE `teacher_globals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `teacher_institute_preferences`
--
ALTER TABLE `teacher_institute_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT for table `teacher_labels`
--
ALTER TABLE `teacher_labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;
--
-- AUTO_INCREMENT for table `teacher_location_preferences`
--
ALTER TABLE `teacher_location_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=598;
--
-- AUTO_INCREMENT for table `teacher_qualifications`
--
ALTER TABLE `teacher_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `teacher_subject_preferences`
--
ALTER TABLE `teacher_subject_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;
--
-- AUTO_INCREMENT for table `teacher_tuition_categories`
--
ALTER TABLE `teacher_tuition_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;
--
-- AUTO_INCREMENT for table `tlabels`
--
ALTER TABLE `tlabels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tuitions`
--
ALTER TABLE `tuitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `tuition_assignment_status`
--
ALTER TABLE `tuition_assignment_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tuition_categories`
--
ALTER TABLE `tuition_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tuition_details`
--
ALTER TABLE `tuition_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;
--
-- AUTO_INCREMENT for table `tuition_globals`
--
ALTER TABLE `tuition_globals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `tuition_history`
--
ALTER TABLE `tuition_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `tuition_institute_preferences`
--
ALTER TABLE `tuition_institute_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `tuition_labels`
--
ALTER TABLE `tuition_labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=618;
--
-- AUTO_INCREMENT for table `tution_status`
--
ALTER TABLE `tution_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;
--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_subject_mappings`
--
ALTER TABLE `class_subject_mappings`
  ADD CONSTRAINT `classid` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `subjectid` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `gid` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `marital_id` FOREIGN KEY (`marital_status_id`) REFERENCES `marital_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher_bookmarks`
--
ALTER TABLE `teacher_bookmarks`
  ADD CONSTRAINT `teacher_bands` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tuitions_id` FOREIGN KEY (`tuition_id`) REFERENCES `tuitions` (`id`);

--
-- Constraints for table `teacher_location_preferences`
--
ALTER TABLE `teacher_location_preferences`
  ADD CONSTRAINT `location_preference` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `teacher_location` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher_qualifications`
--
ALTER TABLE `teacher_qualifications`
  ADD CONSTRAINT `teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `teacher_subject_preferences`
--
ALTER TABLE `teacher_subject_preferences`
  ADD CONSTRAINT `teacher_idp` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tuition_details`
--
ALTER TABLE `tuition_details`
  ADD CONSTRAINT `tuitionid` FOREIGN KEY (`tuition_id`) REFERENCES `tuitions` (`id`);

--
-- Constraints for table `tuition_history`
--
ALTER TABLE `tuition_history`
  ADD CONSTRAINT `teach_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_has_permissions`
--
ALTER TABLE `user_has_permissions`
  ADD CONSTRAINT `user_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_has_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_has_roles`
--
ALTER TABLE `user_has_roles`
  ADD CONSTRAINT `user_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_has_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
