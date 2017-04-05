pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile'
    }
    
  }
  stages {
    stage('') {
      steps {
        parallel(
          "Lint": {
            sh 'php -l'
            
          },
          "PHPUnit": {
            sh 'phpunit'
            
          },
          "PHPCS": {
            sh 'phpcs'
            
          },
          "PHPMD": {
            sh 'phpmd'
            
          }
        )
      }
    }
  }
}