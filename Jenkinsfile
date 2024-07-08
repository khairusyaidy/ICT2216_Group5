pipeline {
  agent any

  stages {
    stage('Checkout SCM') {
      steps {
        git(url: 'https://github.com/muhdmarhakim/ICT2216_Group5.git', branch: 'main')
      }
    }

    stage('OWASP DependencyCheck') {
      steps {
        dependencyCheck(additionalArguments: '--format HTML --format XML', odcInstallation: 'OWASP Dependency-Check Vulnerabilities')
      }
    }

    stage('Composer Install and Test') {
      agent {
        docker {
          image 'composer:latest'
        }
      }
      steps {
        sh 'composer install'
        sh 'mkdir -p logs' // Ensure logs directory exists
        sh './vendor/bin/phpunit --log-junit logs/unitreport.xml -c tests/phpunit.xml tests'
      }
    }
  }

  post {
    always {
      script {
        // Check if the test result file exists before archiving
        def resultFile = 'logs/unitreport.xml'
        if (fileExists(resultFile)) {
          junit resultFile
        } else {
          echo "Test results not found in ${resultFile}"
        }
      }
    }
    failure {
      // Print the contents of the logs directory for debugging
      sh 'ls -l logs'
      // Print the contents of the PHPUnit log file if it exists
      script {
        def resultFile = 'logs/unitreport.xml'
        if (fileExists(resultFile)) {
          sh "cat ${resultFile}"
        } else {
          echo "Test results not found in ${resultFile}"
        }
      }
    }
  }
}
