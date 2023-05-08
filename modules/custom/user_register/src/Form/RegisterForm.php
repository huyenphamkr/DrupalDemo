<?php
/**
 * @file
 * Contains \Drupal\user_register\Form\RegisterForm.
 */
namespace Drupal\user_register\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class RegisterForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_register_form';
  }
  
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Full Name:'),
      '#required' => TRUE,
    );
    $form['user_phone'] = array (
        '#type' => 'tel',
        '#title' => t('Enter Phone Number:'),
        '#required' => TRUE,
    );
    $form['user_mail'] = array(
      '#type' => 'email',
      '#title' => t('Enter Email:'),
    );    
    $form['user_age'] = array (
      '#type' => 'number',
      '#title' => t('Enter Age:'),
    );
    $form['user_des'] = array (
        '#type' => 'textarea',
        '#title' => t('Enter Describe yourself:'),
      );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {   
   
    if(!empty($form_state->getValue('user_mail')))
    {
      if (strpos($form_state->getValue('user_mail'), '@gmail.com') == false) {
        $form_state->setErrorByName('user_mail', $this->t('Please enter a valid Email! Email in the form @gmail.com'));
      }
    }
    if(strlen($form_state->getValue('user_phone')) != 10) {
      $form_state->setErrorByName('user_phone', $this->t('Please enter a valid Contact Number'));
    }

    if(!empty($form_state->getValue('user_age')))
    {
      if($form_state->getValue('user_age') < 18) 
      {
        $form_state->setErrorByName('user_age', $this->t('Age Number Invalid, Please enter valid age from 18 years or older'));
      }
    }    
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
		try{ 
      $conn = Database::getConnection();
		  $field = $form_state->getValues();
		  $fields["user_name"] = $field['user_name'];
		  $fields["user_phone"] = $field['user_phone'];
		  $fields["user_mail"] = $field['user_mail'];
		  $fields["user_age"] = $field['user_age'];
      $fields["user_des"] = $field['user_des'];

		  $conn->insert('accounts')
			   ->fields($fields)->execute();
		  \Drupal::messenger()->addMessage($this->t('User register Done! The User data has been succesfully saved'));
      
    }catch(Exception $ex){
      \Drupal::logger('user_register')->error($ex->getMessage());    
    }
  }
}