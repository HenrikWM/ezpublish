<?php
//
// Definition of eZEmailType class
//
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZEmailType ezemailtype.php
  \ingroup eZDatatype
  \brief Stores an email address

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_EMAIL", "ezemail" );

class eZEmailType extends eZDataType
{
    function eZEmailType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_EMAIL, ezi18n( 'kernel/classes/datatypes', "E-mail", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'email' ) ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*
     Private method, only for using inside this class.
    */
    function validateEMailHTTPInput( $email, &$contentObjectAttribute )
    {
        include_once( "lib/ezutils/classes/ezmail.php" );
        if ( !eZMail::validate( $email ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'The email address is not valid.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $email =& $http->postVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            $trimedEmail = trim( $email );

            if ( $trimedEmail == "" )
            {
                // we require user to enter an address only if the attribute is not an informationcollector
                if ( !$classAttribute->attribute( 'is_information_collector' ) and
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The email address is empty.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            else
            {
                // if the entered address is not empty then we should validate it in any case
                return $this->validateEMailHTTPInput( $trimedEmail, $contentObjectAttribute );
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setAttribute( "data_text", $data );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $email =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            $trimedEmail = trim( $email );

            if ( $trimedEmail == "" )
            {
                // if entered email is empty and required then return state invalid
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The email address is empty.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                // if entered email is not empty then we should validate it in any case
                return $this->validateEMailHTTPInput( $trimedEmail, $contentObjectAttribute );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $collectionAttribute->setAttribute( 'data_text', $dataText );
            return true;
        }
        return false;
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the text.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( "data_text" ) ) != '';
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'string';
    }
}

eZDataType::register( EZ_DATATYPESTRING_EMAIL, "ezemailtype" );

?>
